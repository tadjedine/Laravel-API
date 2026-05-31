<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartProduct;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
}