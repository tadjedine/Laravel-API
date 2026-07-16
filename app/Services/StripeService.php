<?php

namespace App\Services;

use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createCheckoutSession(array $summary): Session
    {
        Stripe::setApiKey(config('stripe.secret_key'));

        $lineItems=[];

        foreach ($summary['items'] as $item) {
            $lineItems[]=[
                'price_data' => [
                    'currency'     => config('stripe.currency'),
                    'unit_amount'  => (int) round($item['unit_price'] * 100),
                    'product_data' => ['name' => $item['name']],
                ],
                'quantity' =>$item['quantity'],
            ];
        }

        $shippingCost = $summary['shipping_cost'];
        if($shippingCost)
        {
             $lineItems[]=[
                'price_data' => [
                    'currency'     => config('stripe.currency'),
                    'unit_amount'  => (int) round($shippingCost * 100),
                    'product_data' => ['name' => 'Shipping'],
                ],
                'quantity' =>1,
            ];
        }

        return Session::create([
            'mode'        => 'payment',
            'line_items'  => $lineItems,
            'success_url' => config('app.frontend_url') .'/checkout/confirmation?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => config('app.frontend_url') . '/checkout',
            'metadata'    => [
                'cart_id'        => $summary['cart_id'],
                'customer_id'    => $summary['customer_id'],
                'payment_method' => 'online_pre_payment',
            ],

        ]);
    }
}
