<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Illuminate\Database\Eloquent\Collection;


class OrderService
{
    // ── Valid order state IDs (from ps_order_state) ──────────────────
    //
    //  1  = Awaiting check payment
    //  2  = Payment accepted
    //  3  = Processing in progress
    //  4  = Shipped
    //  5  = Delivered
    //  6  = Canceled
    //  7  = Refunded
    //  8  = Payment error
    //  9  = On backorder (paid)
    // 10  = Awaiting bank wire payment
    // 11  = Remote payment accepted
    // 12  = On backorder (not paid)
    // 13  = Awaiting Cash On Delivery validation
    // 14  = Waiting for payment
    // 15  = Partial refund
    // 16  = Partial payment
    // 17  = Authorized. To be captured by merchant

    /**
     * Update the state of an order.
     *
     * Validates that the state exists in ps_order_state,
     * updates current_state, and sets total_paid_real when
     * the state is "Payment accepted" (state 2).
     *
     * ──────────────────────────────────────────────────────────────────
     * FUTURE: Stripe / PayPal Webhook Integration
     * ──────────────────────────────────────────────────────────────────
     *
     * This method will be called by the webhook handler when a payment
     * gateway confirms (or rejects) a payment. The webhook flow is:
     *
     * 1. Customer completes payment on Stripe/PayPal
     * 2. Gateway sends POST request to: /v1/webhooks/stripe (or /paypal)
     * 3. WebhookController:
     *    a. Verifies the webhook signature (Stripe-Signature header)
     *    b. Extracts the payment_intent_id from the event payload
     *    c. Finds the Order by payment_intent_id (stored on order or in a payments table)
     *    d. Calls this method:
     *       - payment_intent.succeeded  → updateState($orderId, $customerId, 2)  // Payment accepted
     *       - payment_intent.failed     → updateState($orderId, $customerId, 8)  // Payment error
     *       - charge.refunded           → updateState($orderId, $customerId, 7)  // Refunded
     *
     * For the webhook controller, you'll need:
     *    - Route: POST /v1/webhooks/stripe (no auth:sanctum — webhooks don't have user tokens)
     *    - Middleware: verify Stripe signature (stripe/stripe-php SDK has \\Stripe\\Webhook::constructEvent())
     *    - The $customerId check should be skipped for webhook calls (add a $skipOwnershipCheck param or a separate method)
     * ──────────────────────────────────────────────────────────────────
     *
     * @param  int $orderId    The order to update
     * @param  int $customerId The authenticated customer (ownership check)
     * @param  int $newState   The new state ID (must exist in ps_order_state)
     * @return Order
     */
    public function updateState(int $orderId, int $customerId, int $newState): Order
    {
        // 1. Validate the state exists
        $stateExists = DB::table('ps_order_state')
            ->where('id_order_state', $newState)
            ->exists();

        if (!$stateExists) {
            throw new RuntimeException("Invalid order state: {$newState}");
        }

        // 2. Find the order and verify ownership
        $order = Order::query()->findOrFail($orderId);

        if ((int) $order->id_customer !== $customerId) {
            throw new RuntimeException('Order does not belong to this customer.');
        }

        // 3. Update state
        $order->current_state = $newState;
        $order->date_upd = Carbon::now();

        // 4. If transitioning to "Payment accepted" (state 2), mark the full amount as paid
        if ($newState === 2) {
            $order->total_paid_real = $order->total_paid;
        }

        $order->save();

        return $order->refresh();
    }

    /**
     * Get an order by ID, scoped to the customer.
     */
    public function getOrder(int $orderId, int $customerId): Order
    {
        $order = Order::query()
            ->with('details')
            ->findOrFail($orderId);

        if ((int) $order->id_customer !== $customerId) {
            throw new RuntimeException('Order does not belong to this customer.');
        }

        return $order;
    }

    /**
     * List all orders for a customer, newest first.
     */
    public function getCustomerOrders(int $customerId): Collection
    {
        return Order::query()
            ->where('id_customer', $customerId)
            ->orderByDesc('date_add')
            ->get();
    }
}