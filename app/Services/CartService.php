<?php

namespace App\Services;

use App\Exceptions\Cart\InvalidQuantityException;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\ProductAttribute;
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
                throw new RuntimeException('No active cart found for this customer.');
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
                $line->delete();
            } else {
                $minimumQuantity = $this->resolveMinimumQuantity($productId, $idProductAttribute);
                if ($quantity < $minimumQuantity) {
                    throw new RuntimeException("Quantity must be at least {$minimumQuantity} for this product.");
                }

                $line->quantity = $quantity;
                $line->date_add = Carbon::now();
                $line->save();
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
                $line->delete();
            } else {
                $line->quantity -= $quantityToRemove;
                $line->date_add = Carbon::now();
                $line->save();
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

            $line->quantity = $newQuantity;
            $line->date_add = $now;
            $line->save();
        } else {
            if ($quantity < $minimumQuantity) {
                throw new RuntimeException("Quantity must be at least {$minimumQuantity} for this product.");
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

        return [
            'id' => (int) $cart->id_cart,
            'customer_id' => (int) $cart->id_customer,
            'currency_id' => (int) $cart->id_currency,
            'language_id' => (int) $cart->id_lang,
            'shop_id' => (int) $cart->id_shop,
            'items' => $items,
            'total_quantity' => $totalQuantity,
            'subtotal' => round($subtotal, 2),
            'is_ordered' => $cart->relationLoaded('order')
                ? $cart->order->isNotEmpty()
                : $cart->order()->exists(),
            'updated_at' => $cart->date_upd?->toDateTimeString(),
        ];
    }
}
