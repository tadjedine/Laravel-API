<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Cart;

trait CheckoutTrait
{
    //
    protected function resolveCustomerId(Request $request): int
    {
        if ($user = $request->user()) {
            return (int) $user->id_customer;
        }

        if ($guestCustomerId = $request->attributes->get('guest_customer_id')) {
            return (int) $guestCustomerId;
        }

        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('No active customer session found.');
    }

    /**
     * Resolve the cart ID for checkout.
     *
     * Uses cart_id from the request body if provided,
     * otherwise finds the customer's latest open cart.
     */
    protected function resolveCartId(Request $request): int
    {
        if ($request->filled('cart_id')) {
            return (int) $request->input('cart_id');
        }

        // Find latest open cart for the customer/guest
        $cart = Cart::query()
            ->where('id_customer', $this->resolveCustomerId($request))
            ->whereDoesntHave('order')
            ->orderByDesc('id_cart')
            ->first();

        if (!$cart) {
            throw new \RuntimeException('No active cart found. Please add items to your cart first.');
        }

        return (int) $cart->id_cart;
    }
}
