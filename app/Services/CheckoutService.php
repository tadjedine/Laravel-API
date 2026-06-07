<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class CheckoutService
{
    public function __construct(
        private CartService $cartService,
        private CartRuleService $cartRuleService,
        private AddressService $addressService,
    ) {}

    public function setAddresses(int $id_cart, int $id_address_delivery, ?int $id_address_invoice): Cart
    {
        $cart = $this->cartService->getCartOrFail($id_cart);
        $customerId = (int) $cart->id_customer;

        // Validate delivery address exists and belongs to this customer
        // (firstOrFail inside getAddress throws 404 if not found)
        $this->addressService->getAddress($id_address_delivery, $customerId);

        // If no separate invoice address, use delivery address for both
        if ($id_address_invoice) {
            $this->addressService->getAddress($id_address_invoice, $customerId);
        } else {
            $id_address_invoice = $id_address_delivery;
        }

        return DB::transaction(function () use ($cart, $id_address_delivery, $id_address_invoice) {
            // Update the cart row
            $cart->id_address_delivery = $id_address_delivery;
            $cart->id_address_invoice = $id_address_invoice;
            $cart->date_upd = Carbon::now();
            $cart->save();

            // Bulk-update all cart product rows (CartProduct has no primary key,
            // so model-level update() doesn't work — must use a query builder)
            CartProduct::query()
                ->where('id_cart', $cart->id_cart)
                ->update(['id_address_delivery' => $id_address_delivery]);

            return $cart->refresh();
        });
    }

    public function setCarrier(int $id_cart, int $id_carrier): Cart
    {
        $cart = $this->cartService->getCartOrFail($id_cart);

        // Validate the carrier exists, is active, and not soft-deleted
        $carrier = DB::table('ps_carrier')
            ->where('id_carrier', $id_carrier)
            ->where('active', 1)
            ->where('deleted', 0)
            ->first();

        if (!$carrier) {
            throw new RuntimeException('Carrier not found or not available.');
        }

        $cart->id_carrier = $id_carrier;
        $cart->date_upd = Carbon::now();
        $cart->save();

        return $cart->refresh();
    }

    /**
     * Build the full checkout summary for the frontend to display.
     */
    public function getSummary(int $id_cart, int $customerId): array
    {
        $cart = $this->cartService->getCartOrFail($id_cart);

        // Verify the cart belongs to this customer
        if ((int) $cart->id_customer !== $customerId) {
            throw new RuntimeException('Cart does not belong to this customer.');
        }

        // Load relationships needed for the summary
        $cart->load(['products.product.images', 'products.combination', 'cartRules.langs', 'order']);

        // ── Addresses ───────────────────────────────────────────────
        $deliveryAddress = null;
        $invoiceAddress = null;

        if ((int) $cart->id_address_delivery > 0) {
            $deliveryAddress = Address::query()->find($cart->id_address_delivery);
        }

        if ((int) $cart->id_address_invoice > 0) {
            $invoiceAddress = Address::query()->find($cart->id_address_invoice);
        }

        // ── Carrier ─────────────────────────────────────────────────
        $carrier = null;
        if ((int) $cart->id_carrier > 0) {
            $carrier = DB::table('ps_carrier')
                ->where('id_carrier', $cart->id_carrier)
                ->first();
        }

        // ── Items & subtotal ────────────────────────────────────────
        $items = [];
        $subtotal = 0.0;
        $totalQuantity = 0;

        foreach ($cart->products as $line) {
            $basePrice = (float) ($line->product?->price ?? 0);
            $attributeImpact = (float) ($line->combination?->price ?? 0);
            $unitPrice = $basePrice + $attributeImpact;
            $qty = (int) $line->quantity;
            $lineTotal = round($unitPrice * $qty, 2);

            $items[] = [
                'product_id'           => (int) $line->id_product,
                'product_attribute_id' => (int) $line->id_product_attribute,
                'quantity'             => $qty,
                'unit_price'           => round($unitPrice, 6),
                'line_subtotal'        => $lineTotal,
                'name'                 => $line->product?->name,
                'reference'            => $line->product?->reference,
                'image'                => $line->product?->images?->first()?->id_image,
            ];

            $subtotal += $lineTotal;
            $totalQuantity += $qty;
        }

        $subtotal = round($subtotal, 2);

        // ── Discounts ───────────────────────────────────────────────
        $discountSummary = $this->cartRuleService->computeDiscount($cart);
        $totalAfterDiscount = (float) $discountSummary['subtotal_after_discount'];

        // ── Shipping (v1: simplified) ───────────────────────────────
        // Free if carrier is_free = 1 or if a cart rule grants free shipping
        $isFreeShipping = $discountSummary['free_shipping']
            || ($carrier && (int) $carrier->is_free === 1);

        $shippingCost = $isFreeShipping ? 0.0 : 7.0; // flat-rate placeholder for v1

        // ── Total ───────────────────────────────────────────────────
        $total = round($totalAfterDiscount + $shippingCost, 2);

        // ── Validation ──────────────────────────────────────────────
        $validationErrors = $this->cartService->validateForCheckout($cart);

        // Add address/carrier readiness checks
        if ((int) $cart->id_address_delivery === 0) {
            $validationErrors->push(['type' => 'no_delivery_address', 'message' => 'A delivery address is required.']);
        }
        if ((int) $cart->id_carrier === 0) {
            $validationErrors->push(['type' => 'no_carrier', 'message' => 'A shipping carrier must be selected.']);
        }

        return [
            'cart_id'            => (int) $cart->id_cart,
            'customer_id'        => (int) $cart->id_customer,
            'delivery_address'   => $deliveryAddress ? $this->formatAddress($deliveryAddress) : null,
            'invoice_address'    => $invoiceAddress ? $this->formatAddress($invoiceAddress) : null,
            'carrier'            => $carrier ? [
                'id'       => (int) $carrier->id_carrier,
                'name'     => $carrier->name,
                'is_free'  => (bool) $carrier->is_free,
            ] : null,
            'items'              => $items,
            'total_quantity'     => $totalQuantity,
            'subtotal'           => $subtotal,
            'discount_summary'   => $discountSummary,
            'total_discounts'    => (float) $discountSummary['total_discount'],
            'shipping_cost'      => $shippingCost,
            'total'              => $total,
            'is_ready'           => $validationErrors->isEmpty(),
            'validation_errors'  => $validationErrors->values()->toArray(),
        ];
    }

    /**
     * Finalize checkout: convert cart into an order.
     */
    public function confirm(int $id_cart, int $customerId, PaymentMethod $paymentMethod): Order
    {
        return DB::transaction(function () use ($id_cart, $customerId, $paymentMethod) {

            // 1. Lock the cart to prevent race conditions (double-order)
            $cart = Cart::query()
                ->whereKey($id_cart)
                ->lockForUpdate()
                ->firstOrFail();

            // 2. Verify ownership
            if ((int) $cart->id_customer !== $customerId) {
                throw new RuntimeException('Cart does not belong to this customer.');
            }

            // 3. Ensure the cart hasn't already been ordered
            if ($cart->order()->exists()) {
                throw new RuntimeException('This cart has already been converted to an order.');
            }

            // 4. Load relationships
            $cart->load(['products.product', 'products.combination', 'cartRules.langs']);

            // 5. Validate cart readiness
            $errors = $this->cartService->validateForCheckout($cart);
            if ((int) $cart->id_address_delivery === 0) {
                $errors->push(['type' => 'no_delivery_address', 'message' => 'A delivery address is required.']);
            }
            if ((int) $cart->id_carrier === 0) {
                $errors->push(['type' => 'no_carrier', 'message' => 'A shipping carrier must be selected.']);
            }
            if ($errors->isNotEmpty()) {
                throw new RuntimeException(
                    'Cart is not ready for checkout: ' . $errors->pluck('message')->implode('; ')
                );
            }

            // 6. Compute totals
            $discountSummary = $this->cartRuleService->computeDiscount($cart);

            $totalProducts = 0.0;
            foreach ($cart->products as $line) {
                $basePrice = (float) ($line->product?->price ?? 0);
                $impact    = (float) ($line->combination?->price ?? 0);
                $totalProducts += ($basePrice + $impact) * (int) $line->quantity;
            }
            $totalProducts = round($totalProducts, 2);

            $totalDiscounts = (float) $discountSummary['total_discount'];

            // Shipping (v1 simplified)
            $carrier = DB::table('ps_carrier')
                ->where('id_carrier', $cart->id_carrier)
                ->first();

            $isFreeShipping = $discountSummary['free_shipping']
                || ($carrier && (int) $carrier->is_free === 1);
            $totalShipping = $isFreeShipping ? 0.0 : 7.0;

            $totalPaid = round($totalProducts - $totalDiscounts + $totalShipping, 2);

            // 7. Generate PrestaShop-style order reference (9-char uppercase alpha)
            $reference = Str::upper(Str::random(9));

            $now = Carbon::now();

            // 8. Create the Order
            $order = Order::query()->create([
                'reference'               => $reference,
                'id_shop_group'           => (int) $cart->id_shop_group,
                'id_shop'                 => (int) $cart->id_shop,
                'id_carrier'              => (int) $cart->id_carrier,
                'id_lang'                 => (int) $cart->id_lang,
                'id_customer'             => (int) $cart->id_customer,
                'id_cart'                 => (int) $cart->id_cart,
                'id_currency'             => (int) $cart->id_currency,
                'id_address_delivery'     => (int) $cart->id_address_delivery,
                'id_address_invoice'      => (int) $cart->id_address_invoice,
                'current_state'           => $paymentMethod->initialState(),
                'secure_key'              => $cart->secure_key,
                'payment'                 => $paymentMethod->label(),
                'module'                  => $paymentMethod->module(),
                'conversion_rate'         => 1.0,
                'recyclable'              => (int) $cart->recyclable,
                'gift'                    => (int) $cart->gift,
                'gift_message'            => $cart->gift_message,
                'mobile_theme'            => false,
                'total_discounts'         => $totalDiscounts,
                'total_discounts_tax_incl' => $totalDiscounts, // no tax distinction in v1
                'total_discounts_tax_excl' => $totalDiscounts,
                'total_paid'              => $totalPaid,
                'total_paid_tax_incl'     => $totalPaid,
                'total_paid_tax_excl'     => $totalPaid,
                'total_paid_real'         => 0.00, // updated when payment is confirmed
                'total_products'          => $totalProducts,
                'total_products_wt'       => $totalProducts, // wt = with tax (same in v1)
                'total_shipping'          => $totalShipping,
                'total_shipping_tax_incl' => $totalShipping,
                'total_shipping_tax_excl' => $totalShipping,
                'carrier_tax_rate'        => 0.0,
                'total_wrapping'          => 0.0,
                'total_wrapping_tax_incl' => 0.0,
                'total_wrapping_tax_excl' => 0.0,
                'round_mode'              => false,
                'round_type'              => false,
                'invoice_number'          => 0,
                'delivery_number'         => 0,
                'invoice_date'            => $now,
                'delivery_date'           => $now,
                'valid'                   => 0,
                'date_add'                => $now,
                'date_upd'                => $now,
            ]);

            // 9. Create OrderDetail rows for each cart product
            foreach ($cart->products as $line) {
                $product = $line->product;
                if (!$product) {
                    continue;
                }

                $basePrice = (float) $product->price;
                $impact    = (float) ($line->combination?->price ?? 0);
                $unitPrice = $basePrice + $impact;
                $qty       = (int) $line->quantity;
                $lineTotal = round($unitPrice * $qty, 2);

                OrderDetail::query()->create([
                    'id_order'                      => $order->id_order,
                    'id_shop'                       => (int) $cart->id_shop,
                    'product_id'                    => (int) $line->id_product,
                    'product_attribute_id'          => (int) $line->id_product_attribute,
                    'id_customization'              => (int) ($line->id_customization ?? 0),
                    'product_name'                  => $product->name ?? 'Product #' . $product->id_product,
                    'product_quantity'               => $qty,
                    'product_quantity_in_stock'      => $qty,
                    'product_quantity_refunded'      => 0,
                    'product_quantity_return'        => 0,
                    'product_quantity_reinjected'    => 0,
                    'product_price'                 => $basePrice,
                    'reduction_percent'             => 0.0,
                    'reduction_amount'              => 0.0,
                    'reduction_amount_tax_incl'     => 0.0,
                    'reduction_amount_tax_excl'     => 0.0,
                    'group_reduction'               => 0.0,
                    'product_quantity_discount'      => 0.0,
                    'product_ean13'                 => $product->ean13,
                    'product_isbn'                  => $product->isbn,
                    'product_upc'                   => $product->upc,
                    'product_mpn'                   => $product->mpn,
                    'product_reference'             => $product->reference,
                    'product_supplier_reference'    => $product->supplier_reference,
                    'product_weight'                => (float) $product->weight,
                    'id_tax_rules_group'            => (int) $product->id_tax_rules_group,
                    'tax_computation_method'        => 0,
                    'tax_name'                      => '',
                    'tax_rate'                      => 0.0,
                    'ecotax'                        => (float) $product->ecotax,
                    'ecotax_tax_rate'               => 0.0,
                    'discount_quantity_applied'     => false,
                    'total_price_tax_incl'          => $lineTotal,
                    'total_price_tax_excl'          => $lineTotal,
                    'unit_price_tax_incl'           => $unitPrice,
                    'unit_price_tax_excl'           => $unitPrice,
                    'total_shipping_price_tax_incl' => 0.0,
                    'total_shipping_price_tax_excl' => 0.0,
                    'purchase_supplier_price'       => 0.0,
                    'original_product_price'        => $basePrice,
                    'original_wholesale_price'      => (float) $product->wholesale_price,
                    'total_refunded_tax_excl'       => 0.0,
                    'total_refunded_tax_incl'       => 0.0,
                ]);
            }

            // 10. Copy cart rules to ps_order_cart_rule
            if ($cart->cartRules->isNotEmpty()) {
                foreach ($discountSummary['rules_applied'] as $appliedRule) {
                    $ruleModel = $cart->cartRules->firstWhere('id_cart_rule', $appliedRule['id']);
                    $langName  = $appliedRule['name'] ?? $appliedRule['code'] ?? '';

                    DB::table('ps_order_cart_rule')->insert([
                        'id_order'         => $order->id_order,
                        'id_cart_rule'     => $appliedRule['id'],
                        'id_order_invoice' => 0,
                        'name'             => $langName,
                        'value'            => $appliedRule['discount_amount'],
                        'value_tax_excl'   => $appliedRule['discount_amount'],
                        'free_shipping'    => $appliedRule['free_shipping'] ? 1 : 0,
                        'deleted'          => 0,
                    ]);
                }
            }

            // 11. Return the order with details loaded
            return $order->load('details');
        });
    }

    // ── Helpers ──────────────────────────────────────────────────────

    /**
     * Format an Address model into a plain array for the summary response.
     */
    private function formatAddress(Address $address): array
    {
        return [
            'id'           => (int) $address->id_address,
            'alias'        => $address->alias,
            'firstname'    => $address->firstname,
            'lastname'     => $address->lastname,
            'company'      => $address->company,
            'address1'     => $address->address1,
            'address2'     => $address->address2,
            'postcode'     => $address->postcode,
            'city'         => $address->city,
            'id_country'   => (int) $address->id_country,
            'phone'        => $address->phone,
            'phone_mobile' => $address->phone_mobile,
        ];
    }
}
