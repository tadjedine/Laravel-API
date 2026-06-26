<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\ConfirmCheckoutRequest;
use App\Http\Requests\Checkout\GuestCheckoutRequest;
use App\Http\Requests\Checkout\SetAddressesRequest;
use App\Http\Requests\Checkout\SetCarrierRequest;
use App\Http\Resources\CheckoutResource;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Customer;
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
            $this->resolveCustomerId($request),
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
            $request->paymentMethod(),
        );

        return response()->json([
            'message'   => 'Order created successfully.',
            'order_id'  => (int) $order->id_order,
            'reference' => $order->reference,
            'total'     => (float) $order->total_paid,
            'state'     => (int) $order->current_state,
        ], 201);
    }

    /**
     * Guest checkout — collect contact info, address, and confirm in one step.
     * POST /v1/checkout/guest-confirm
     *
     * This endpoint handles the entire guest checkout flow:
     * 1. Checks if the provided email belongs to a real account (rejects if so).
     * 2. Updates the guest-customer row with real contact info.
     * 3. Creates a shipping address for the guest-customer.
     * 4. Sets address and carrier on the cart.
     * 5. Confirms the order using the existing CheckoutService.
     */
    public function guestConfirm(GuestCheckoutRequest $request): JsonResponse
    {
        $guestCustomerId = (int) $request->attributes->get('guest_customer_id');
        $data = $request->validated();

        // 1. Check if the email belongs to a real (non-guest) account
        $existingReal = Customer::query()
            ->where('email', $data['email'])
            ->where('is_guest', 0)
            ->where('deleted', 0)
            ->first();

        if ($existingReal) {
            return response()->json([
                'message' => 'An account with this email already exists. Please sign in instead.',
            ], 409);
        }

        // 2. Update the guest-customer with real contact info
        $guestCustomer = Customer::query()->findOrFail($guestCustomerId);
        $guestCustomer->update([
            'email'     => $data['email'],
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'date_upd'  => now(),
        ]);

        // 3. Create a shipping address for this guest-customer
        $address = Address::query()->create([
            'id_customer'    => $guestCustomerId,
            'id_country'     => (int) $data['id_country'],
            'id_state'       => 0,
            'id_manufacturer'=> 0,
            'id_supplier'    => 0,
            'id_warehouse'   => 0,
            'alias'          => 'Guest Checkout',
            'firstname'      => $data['firstname'],
            'lastname'       => $data['lastname'],
            'address1'       => $data['address1'],
            'address2'       => $data['address2'] ?? '',
            'postcode'       => $data['postcode'] ?? '',
            'city'           => $data['city'],
            'phone'          => $data['phone'] ?? '',
            'phone_mobile'   => '',
            'active'         => 1,
            'deleted'        => 0,
            'date_add'       => now(),
            'date_upd'       => now(),
        ]);

        // 4. Find the guest's active cart
        $cart = Cart::query()
            ->where('id_customer', $guestCustomerId)
            ->whereDoesntHave('order')
            ->orderByDesc('id_cart')
            ->firstOrFail();

        // 5. Set address and carrier on the cart
        $this->checkoutService->setAddresses(
            (int) $cart->id_cart,
            (int) $address->id_address,
            null
        );
        $this->checkoutService->setCarrier(
            (int) $cart->id_cart,
            (int) $data['id_carrier']
        );

        // 6. Confirm the order (reuses existing logic!)
        $paymentMethod = PaymentMethod::from($data['payment_method']);
        $order = $this->checkoutService->confirm(
            (int) $cart->id_cart,
            $guestCustomerId,
            $paymentMethod,
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

    private function resolveCustomerId(Request $request): int
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
    private function resolveCartId(Request $request): int
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

