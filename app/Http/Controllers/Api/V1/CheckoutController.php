<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\ConfirmCheckoutRequest;
use App\Http\Requests\Checkout\SetAddressesRequest;
use App\Http\Requests\Checkout\SetCarrierRequest;
use App\Http\Resources\CheckoutResource;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private CheckoutService $checkoutService) {}

    /**
     * Set delivery and invoice addresses on the cart.
     * PUT /v1/checkout/addresses
     */
    public function setAddresses(SetAddressesRequest $request): JsonResponse
    {
        $cart = $this->checkoutService->setAddresses(
            $this->resolveCartId($request),
            (int) $request->validated('id_address_delivery'),
            $request->validated('id_address_invoice') ? (int) $request->validated('id_address_invoice') : null,
        );

        return response()->json([
            'message' => 'Addresses set successfully.',
            'cart_id' => (int) $cart->id_cart,
            'id_address_delivery' => (int) $cart->id_address_delivery,
            'id_address_invoice'  => (int) $cart->id_address_invoice,
        ]);
    }

    /**
     * Select a shipping carrier.
     * PUT /v1/checkout/carrier
     */
    public function setCarrier(SetCarrierRequest $request): JsonResponse
    {
        $cart = $this->checkoutService->setCarrier(
            $this->resolveCartId($request),
            (int) $request->validated('id_carrier'),
        );

        return response()->json([
            'message'    => 'Carrier set successfully.',
            'cart_id'    => (int) $cart->id_cart,
            'id_carrier' => (int) $cart->id_carrier,
        ]);
    }

    /**
     * Get the full checkout summary.
     * GET /v1/checkout/summary
     */
    public function summary(Request $request): CheckoutResource
    {
        $summaryData = $this->checkoutService->getSummary(
            $this->resolveCartId($request),
            (int) $request->user()->id_customer,
        );

        return new CheckoutResource($summaryData);
    }

    /**
     * Confirm the checkout — convert cart into an order.
     * POST /v1/checkout/confirm
     */
    public function confirm(ConfirmCheckoutRequest $request): JsonResponse
    {
        $order = $this->checkoutService->confirm(
            $this->resolveCartId($request),
            $request->customerId(),
            $request->validated('payment_method'),
        );

        return response()->json([
            'message'   => 'Order created successfully.',
            'order_id'  => (int) $order->id_order,
            'reference' => $order->reference,
            'total'     => (float) $order->total_paid,
            'state'     => (int) $order->current_state,
        ], 201);
    }

    // ── Helper ──────────────────────────────────────────────────────

    /**
     * Resolve the cart ID for checkout.
     *
     * Uses cart_id from the request body if provided,
     * otherwise finds the customer's latest open cart.
     */
    private function resolveCartId(Request $request): int
    {
        if ($request->filled('cart_id')) {
            return (int) $request->input('cart_id');
        }

        // Find latest open cart for the authenticated customer
        $cart = \App\Models\Cart::query()
            ->where('id_customer', $request->user()->id_customer)
            ->whereDoesntHave('order')
            ->orderByDesc('id_cart')
            ->first();

        if (!$cart) {
            throw new \RuntimeException('No active cart found. Please add items to your cart first.');
        }

        return (int) $cart->id_cart;
    }
}
