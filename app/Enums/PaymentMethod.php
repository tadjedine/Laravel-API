<?php

namespace App\Enums;

/**
 * Supported payment methods for checkout.
 *
 * Each case maps to:
 *  - A frontend key (the backed string value)
 *  - A human-readable label (stored in ps_orders.payment)
 *  - A PrestaShop module name (stored in ps_orders.module)
 *  - An initial order state (stored in ps_orders.current_state)
 *
 * ──────────────────────────────────────────────────────────────────
 * FUTURE: Stripe / PayPal Integration Workflow
 * ──────────────────────────────────────────────────────────────────
 *
 * When implementing real payment gateway integration, the flow is:
 *
 * 1. ONLINE_PRE_PAYMENT (pay-before-order):
 *    - Frontend → Stripe Checkout / PayPal → payment captured
 *    - Frontend receives a payment_intent_id or transaction_id
 *    - Frontend sends: POST /v1/checkout/confirm
 *      { "payment_method": "online_pre_payment", "payment_intent_id": "pi_xxx" }
 *    - Backend verifies the payment with Stripe API before creating the order
 *    - Order starts at state 2 (Payment accepted) since money is already captured
 *
 * 2. ONLINE_POST_PAYMENT (order-before-pay):
 *    - Frontend sends: POST /v1/checkout/confirm
 *      { "payment_method": "online_post_payment" }
 *    - Order created at state 14 (Waiting for payment)
 *    - Backend creates a Stripe PaymentIntent and returns client_secret to frontend
 *    - Frontend uses Stripe.js to complete payment
 *    - Stripe sends webhook to: POST /v1/webhooks/stripe
 *    - Webhook handler verifies signature, finds order by payment_intent metadata,
 *      and calls OrderService::updateState($orderId, 2) → Payment accepted
 *    - If payment fails, webhook sets state to 8 (Payment error)
 *
 * 3. CASH_ON_DELIVERY:
 *    - No payment gateway involved
 *    - Order starts at state 13 (Awaiting COD validation)
 *    - When delivery driver confirms collection:
 *      PUT /v1/orders/{id}/state { "state": 2 }
 *    - This would typically be an admin-only endpoint
 *
 * Required for gateway integration:
 *    - A payment_intent_id / transaction_id column on ps_orders (or a separate payments table)
 *    - A webhook controller: App\Http\Controllers\Api\V1\WebhookController
 *    - Stripe SDK: composer require stripe/stripe-php
 *    - Webhook signature verification middleware
 * ──────────────────────────────────────────────────────────────────
 */
enum PaymentMethod: string
{
    case CASH_ON_DELIVERY   = 'cash_on_delivery';
    case ONLINE_PRE_PAYMENT = 'online_pre_payment';
    case ONLINE_POST_PAYMENT = 'online_post_payment';

    /**
     * Human-readable name stored in ps_orders.payment
     */
    public function label(): string
    {
        return match ($this) {
            self::CASH_ON_DELIVERY   => 'Cash on Delivery',
            self::ONLINE_PRE_PAYMENT => 'Online Payment',
            self::ONLINE_POST_PAYMENT => 'Online Payment',
        };
    }

    /**
     * PrestaShop module identifier stored in ps_orders.module
     */
    public function module(): string
    {
        return match ($this) {
            self::CASH_ON_DELIVERY   => 'ps_cashondelivery',
            self::ONLINE_PRE_PAYMENT => 'ps_onlinepayment',
            self::ONLINE_POST_PAYMENT => 'ps_onlinepayment',
        };
    }

    /**
     * Initial order state when the order is first created.
     *
     * @see ps_order_state table for all available states
     */
    public function initialState(): int
    {
        return match ($this) {
            self::CASH_ON_DELIVERY   => 13, // Awaiting Cash On Delivery validation
            self::ONLINE_PRE_PAYMENT => 2,  // Payment accepted (already paid)
            self::ONLINE_POST_PAYMENT => 14, // Waiting for payment
        };
    }
}
