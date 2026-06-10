<?php

namespace App\Services;

use App\Exceptions\Cart\CartEmptyException;
use App\Exceptions\Cart\CartNotFoundException;
use App\Exceptions\Cart\InvalidQuantityException;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\StockAvailable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class CartService
{
    private array $cartRelations = [
        'products.product.images',
        'products.combination',
        'order',
        'CartRules.langs'
    ];

    public function getOrCreateCart(int $customerId, array $context = []): Cart
    {
        $cart = $this->findLatestOpenCartByCustomer($customerId);

        if (! $cart) {
            $cart = $this->createCart(array_merge($context, ['id_customer' => $customerId]));
        }

        $cart->load(['products.product.images', 'products.combination', 'order']);

        return $this->loadCartGraph($cart);
    }

    public function addItem(int $customerId, int $productId, int $quantity = 1, array $context = []): Cart
    {
        if ($quantity < 1) {
            throw new InvalidQuantityException();
        }

        $cart = DB::transaction(function () use ($customerId, $productId, $quantity, $context) {
            $cart = $this->findLatestOpenCartByCustomer($customerId);

            if (! $cart) {
                $cart = $this->createCart(array_merge($context, ['id_customer' => $customerId]));
            }

            $lockedCart = Cart::query()->whereKey($cart->id_cart)->lockForUpdate()->firstOrFail();

            $this->upsertCartLine($lockedCart, $productId, $quantity, $context, true);

            return $lockedCart;
        });

        $cart->load(['products.product.images', 'products.combination', 'order']);

        return $this->loadCartGraph($cart);
    }

    public function updateItemQuantity(int $customerId, int $productId, int $quantity, array $context = []): Cart
    {
        if ($quantity < 0) {
            throw new RuntimeException('Quantity cannot be negative.');
        }

        $cart = DB::transaction(function () use ($customerId, $productId, $quantity, $context) {
            $cart = $this->findLatestOpenCartByCustomer($customerId);

            if (! $cart) {
                throw new CartNotFoundException();
            }

            $lockedCart = Cart::query()->whereKey($cart->id_cart)->lockForUpdate()->firstOrFail();

            $idProductAttribute = (int) ($context['id_product_attribute'] ?? 0);
            $idCustomization = (int) ($context['id_customization'] ?? 0);
            $idAddressDelivery = array_key_exists('id_address_delivery', $context)
                ? (int) $context['id_address_delivery']
                : (int) $lockedCart->id_address_delivery;

            $line = CartProduct::query()
                ->where('id_cart', $lockedCart->id_cart)
                ->where('id_product', $productId)
                ->where('id_product_attribute', $idProductAttribute)
                ->where('id_customization', $idCustomization)
                ->where('id_address_delivery', $idAddressDelivery)
                ->lockForUpdate()
                ->first();

            if (! $line) {
                throw new RuntimeException('Product is not present in the cart.');
            }

            if ($quantity === 0) {
                $this->deleteLine($line);
            } else {
                $minimumQuantity = $this->resolveMinimumQuantity($productId, $idProductAttribute);
                if ($quantity < $minimumQuantity) {
                    throw new RuntimeException("Quantity must be at least {$minimumQuantity} for this product.");
                }

                $stock = StockAvailable::query()
                    ->where('id_product', $productId)
                    ->where('id_product_attribute', $idProductAttribute)
                    ->value('quantity') ?? 0;

                if ($quantity > $stock) {
                    throw new RuntimeException("Not enough stock available. Only {$stock} remaining.");
                }

                $line->quantity = $quantity;
                $line->date_add = Carbon::now();

                //$line->save();
                CartProduct::query()
                ->where('id_cart', $line->id_cart)
                ->where('id_product', $line->id_product)
                ->where('id_product_attribute', $line->id_product_attribute)
                ->where('id_customization', $line->id_customization)
                ->where('id_address_delivery', $line->id_address_delivery)
                ->update(['quantity' => $line->quantity, 'date_add' => $line->date_add]);
            }

            $lockedCart->date_upd = Carbon::now();
            $lockedCart->save();

            return $lockedCart;
        });

        $cart->load(['products.product.images', 'products.combination', 'order']);

        return $this->loadCartGraph($cart);
    }

    public function removeItem(int $customerId, int $productId, array $context = []): Cart
    {
        return $this->updateItemQuantity($customerId, $productId, 0, $context);
    }

    public function clearItems(int $customerId): array
    {
        $cart = DB::transaction(function () use ($customerId) {
            $cart = $this->findLatestOpenCartByCustomer($customerId);

            if (! $cart) {
                throw new RuntimeException('No active cart found for this customer.');
            }

            $lockedCart = Cart::query()->whereKey($cart->id_cart)->lockForUpdate()->firstOrFail();

            CartProduct::query()->where('id_cart', $lockedCart->id_cart)->delete();

            $lockedCart->date_upd = Carbon::now();
            $lockedCart->save();

            return $lockedCart;
        });

        $cart->load(['products.product.images', 'products.combination', 'order']);

        return $this->normalizeCart($cart);
    }

    public function createCart(array $data): Cart
    {
        $defaults = $this->resolveCartDefaults();
        $now = Carbon::now();

        $cartData = array_merge([
            'id_shop_group' => $defaults['id_shop_group'],
            'id_shop' => $defaults['id_shop'],
            'id_carrier' => 0,
            'delivery_option' => '',
            'id_lang' => $defaults['id_lang'],
            'id_address_delivery' => 0,
            'id_address_invoice' => 0,
            'id_currency' => $defaults['id_currency'],
            'id_customer' => 0,
            'id_guest' => 0,
            'secure_key' => Str::random(32),
            'recyclable' => 0,
            'gift' => 0,
            'gift_message' => null,
            'mobile_theme' => false,
            'allow_seperated_package' => 0,
            'date_add' => $now,
            'date_upd' => $now,
            'checkout_session_data' => null,
        ], $data);

        if (empty($cartData['id_lang']) || empty($cartData['id_currency'])) {
            throw new RuntimeException('Cart creation requires id_lang and id_currency.');
        }

        return Cart::query()->create($cartData);
    }

    public function addProduct(
        int $idCart,
        int $idProduct,
        int $quantity,
        int $idProductAttribute = 0,
        int $idCustomization = 0,
        int $idAddressDelivery = 0
    ): CartProduct {
        if ($quantity < 1) {
            throw new RuntimeException('Quantity must be at least 1.');
        }

        return DB::transaction(function () use ($idCart, $idProduct, $quantity, $idProductAttribute, $idCustomization, $idAddressDelivery) {
            $cart = Cart::query()->whereKey($idCart)->lockForUpdate()->firstOrFail();

            return $this->upsertCartLine($cart, $idProduct, $quantity, [
                'id_product_attribute' => $idProductAttribute,
                'id_customization' => $idCustomization,
                'id_address_delivery' => $idAddressDelivery,
            ], true);
        });
    }

    public function removeProduct(
        int $idCart,
        int $idProduct,
        int $idProductAttribute = 0,
        int $idCustomization = 0,
        int $idAddressDelivery = 0,
        ?int $quantityToRemove = null
    ): void {
        DB::transaction(function () use ($idCart, $idProduct, $idProductAttribute, $idCustomization, $idAddressDelivery, $quantityToRemove) {
            $line = CartProduct::query()
                ->where('id_cart', $idCart)
                ->where('id_product', $idProduct)
                ->where('id_product_attribute', $idProductAttribute)
                ->where('id_customization', $idCustomization)
                ->where('id_address_delivery', $idAddressDelivery)
                ->lockForUpdate()
                ->first();

            if (! $line) {
                return;
            }

            if ($quantityToRemove === null || $line->quantity <= $quantityToRemove) {
                $line->deleteLine();
            } else {
                $line->quantity -= $quantityToRemove;
                $line->date_add = Carbon::now();
               // $line->save();
               CartProduct::query()
                    ->where('id_cart', $line->id_cart)
                    ->where('id_product', $line->id_product)
                    ->where('id_product_attribute', $line->id_product_attribute)
                    ->where('id_customization', $line->id_customization)
                    ->where('id_address_delivery', $line->id_address_delivery)
                    ->update(['quantity' => $line->quantity, 'date_add' => $line->date_add]);
            }

            Cart::query()->whereKey($idCart)->update(['date_upd' => Carbon::now()]);
        });
    }

    public function getCart(int $idCart): ?Cart
    {
        return Cart::query()
            ->with(['products.product.images', 'products.combination', 'order'])
            ->find($idCart);
    }

    public function getCartOrFail(int $idCart): Cart
    {
        $cart = $this->getCart($idCart);

        if (! $cart) {
            throw new CartNotFoundException($idCart);
        }

        return $cart;
    }

    
    /**
     * General check for the cart before moving to checkout.
     * Returns a collection of errors (empty = cart is valid).
     */
    public function validateForCheckout(Cart $cart): \Illuminate\Support\Collection
    {
        $errors = collect();

        // Cart must not already be ordered
        if ($cart->order()->exists()) {
            $errors->push(['type' => 'already_ordered', 'message' => 'This cart has already been converted to an order.']);
            return $errors;
        }

        // Cart must not be empty
        if (!$cart->items()->exists()) {
            $errors->push(['type' => 'empty_cart', 'message' => "Can't checkout an empty cart."]);
            return $errors;
        }

        // Validate each product line
        foreach ($cart->productModels as $product) {
            $productId = (int) $product->id_product;
            $quantity = (int) $product->pivot->quantity;
            $attributeId = (int) ($product->pivot->id_product_attribute ?? 0);

            if ((int) $product->active !== 1) {
                $errors->push(['type' => 'product_inactive', 'message' => "Product {$productId} is no longer active."]);
            }

            if (!$product->available_for_order) {
                $errors->push(['type' => 'product_unavailable', 'message' => "Product {$productId} is not available for order."]);
            }

            $minimumQuantity = $this->resolveMinimumQuantity($productId, $attributeId);
            if ($quantity < $minimumQuantity) {
                $errors->push(['type' => 'minimum_quantity', 'message' => "Product {$productId} requires a minimum quantity of {$minimumQuantity}."]);
            }

            $stock = StockAvailable::query()
                ->where('id_product', $productId)
                ->where('id_product_attribute', $attributeId)
                ->value('quantity') ?? 0;

            if ($quantity > $stock) {
                $errors->push(['type' => 'out_of_stock', 'message' => "Product {$productId} does not have enough stock ({$stock} remaining)."]);
            }
        }

        return $errors;
    }

    // ******************** Helper Methods *******************************

    private function loadCartGraph(Cart $cart): Cart
    {
        return $cart->load($this->cartRelations);
    }

    private function upsertCartLine(
        Cart $cart,
        int $productId,
        int $quantity,
        array $context,
        bool $increment = true
    ): CartProduct {
        $product = Product::query()->find($productId);

        if (! $product) {
            throw new RuntimeException('Product not found.');
        }

        if ((int) $product->active !== 1 || (bool) $product->available_for_order !== true) {
            throw new RuntimeException('Product is not available for order.');
        }

        $idProductAttribute = (int) ($context['id_product_attribute'] ?? 0);
        $idCustomization = (int) ($context['id_customization'] ?? 0);
        $idAddressDelivery = array_key_exists('id_address_delivery', $context)
            ? (int) $context['id_address_delivery']
            : (int) $cart->id_address_delivery;

        $line = CartProduct::query()
            ->where('id_cart', $cart->id_cart)
            ->where('id_product', $productId)
            ->where('id_product_attribute', $idProductAttribute)
            ->where('id_customization', $idCustomization)
            ->where('id_address_delivery', $idAddressDelivery)
            ->lockForUpdate()
            ->first();

        $minimumQuantity = $this->resolveMinimumQuantity($productId, $idProductAttribute);
        $now = Carbon::now();

        if ($line) {
            $newQuantity = $increment ? ($line->quantity + $quantity) : $quantity;

            if ($newQuantity < $minimumQuantity) {
                throw new RuntimeException("Quantity must be at least {$minimumQuantity} for this product.");
            }

            $stock = StockAvailable::query()
                ->where('id_product', $productId)
                ->where('id_product_attribute', $idProductAttribute)
                ->value('quantity') ?? 0;

            if ($newQuantity > $stock) {
                throw new RuntimeException("Not enough stock available. Only {$stock} remaining.");
            }

            $line->quantity = $newQuantity;
            $line->date_add = $now;
            // $line->save();
            CartProduct::query()
                ->where('id_cart', $line->id_cart)
                ->where('id_product', $line->id_product)
                ->where('id_product_attribute', $line->id_product_attribute)
                ->where('id_customization', $line->id_customization)
                ->where('id_address_delivery', $line->id_address_delivery)
                ->update(['quantity' => $line->quantity, 'date_add' => $line->date_add]);
        } else {
            if ($quantity < $minimumQuantity) {
                throw new RuntimeException("Quantity must be at least {$minimumQuantity} for this product.");
            }

            $stock = StockAvailable::query()
                ->where('id_product', $productId)
                ->where('id_product_attribute', $idProductAttribute)
                ->value('quantity') ?? 0;

            if ($quantity > $stock) {
                throw new RuntimeException("Not enough stock available. Only {$stock} remaining.");
            }

            $line = CartProduct::query()->create([
                'id_cart' => $cart->id_cart,
                'id_product' => $productId,
                'id_address_delivery' => $idAddressDelivery,
                'id_shop' => (int) $cart->id_shop,
                'id_product_attribute' => $idProductAttribute,
                'id_customization' => $idCustomization,
                'quantity' => $quantity,
                'date_add' => $now,
            ]);
        }

        $cart->date_upd = $now;
        $cart->save();

        return $line;
    }

    private function findLatestOpenCartByCustomer(int $customerId): ?Cart
    {
        return Cart::query()
            ->where('id_customer', $customerId)
            ->whereDoesntHave('order')
            ->orderByDesc('id_cart')
            ->first();
    }

    private function resolveMinimumQuantity(int $productId, int $idProductAttribute = 0): int
    {
        if ($idProductAttribute > 0) {
            $attribute = ProductAttribute::query()
                ->where('id_product_attribute', $idProductAttribute)
                ->where('id_product', $productId)
                ->first();

            if ($attribute && (int) $attribute->minimal_quantity > 0) {
                return (int) $attribute->minimal_quantity;
            }
        }

        $product = Product::query()->find($productId);

        if (! $product) {
            return 1;
        }

        return max(1, (int) $product->minimal_quantity);
    }

    private function resolveCartDefaults(): array
    {
        return [
            'id_shop' => (int) config('prestashop.default_shop_id', 1),
            'id_shop_group' => (int) config('prestashop.default_shop_group_id', 1),
            'id_currency' => (int) config('prestashop.default_currency_id', 1),
            'id_lang' => (int) config('prestashop.default_lang_id', 1),
        ];
    }

    private function normalizeCart(Cart $cart): array
    {
        $items = [];
        $totalQuantity = 0;
        $subtotal = 0.0;

        foreach ($cart->products as $line) {
            $basePrice = (float) ($line->product?->price ?? 0);
            $attributeImpact = (float) ($line->combination?->price ?? 0);
            $unitPrice = $basePrice + $attributeImpact;
            $lineSubtotal = $unitPrice * (int) $line->quantity;

            $items[] = [
                'product_id' => (int) $line->id_product,
                'product_attribute_id' => (int) $line->id_product_attribute,
                'quantity' => (int) $line->quantity,
                'unit_price' => round($unitPrice, 6),
                'line_subtotal' => round($lineSubtotal, 2),
                'name' => $line->product?->name ?? null,
                'reference' => $line->product?->reference ?? null,
                'image' => $line->product?->images?->first()?->id_image ?? null,
            ];

            $totalQuantity += (int) $line->quantity;
            $subtotal += $lineSubtotal;
        }

        $subtotal = round($subtotal, 2);

        $discountSummary    = null;
        $totalAfterDiscount = $subtotal;

        if ($cart->relationLoaded('cartRules') && $cart->cartRules->isNotEmpty()) {
            $service            = app(CartRuleService::class);
            $discountSummary    = $service->computeDiscount($cart);
            $totalAfterDiscount = $discountSummary['subtotal_after_discount'];
        }

        return [
            'id' => (int) $cart->id_cart,
            'customer_id' => (int) $cart->id_customer,
            'currency_id' => (int) $cart->id_currency,
            'language_id' => (int) $cart->id_lang,
            'shop_id' => (int) $cart->id_shop,
            'items' => $items,
            'total_quantity' => $totalQuantity,
            'subtotal' => $subtotal,
            'discount_summary' => $discountSummary,
            'total_after_discount' => round($totalAfterDiscount, 2),
            'is_ordered' => $cart->relationLoaded('order')
                ? $cart->order->isNotEmpty()
                : $cart->order()->exists(),
            'updated_at' => $cart->date_upd?->toDateTimeString(),
        ];
    }

    private function deleteLine($line)
    {
        CartProduct::query()
            ->where('id_cart', $line->id_cart)
            ->where('id_product', $line->id_product)
            ->where('id_product_attribute', $line->id_product_attribute)
            ->where('id_customization', $line->id_customization)
            ->where('id_address_delivery', $line->id_address_delivery)
            ->delete();
    }
}
