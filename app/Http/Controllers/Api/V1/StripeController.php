<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\CheckoutTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\CheckoutService;
use App\Services\StripeService;
use Stripe;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use App\Enums\PaymentMethod;
use DB;

class StripeController extends Controller
{
    //
    use CheckoutTrait;

    public function __construct
    (   private CheckoutService $checkoutService,
        private StripeService $stripeService
    ) {}


    public function createCheckoutSession(Request $request): JsonResponse
    {
        $cartId= $this->resolveCartId($request);
        $customerId = $this->resolveCustomerId($request);

        $summary = $this->checkoutService->getSummary($cartId, $customerId);

        if (!$summary['is_ready']) {
            return response()->json([
                'message' => 'Cart is not ready for checkout.',
                'errors'  => $summary['validation_errors'],
            ], 422);
        }

        $session = $this->stripeService->createCheckoutSession($summary);


        return response()->json([
            'url' => $session->url,
        ]);
    }


    public function handleWebhook(Request $request)
    {        
        $payload = $request->getContent();
        $event = null;
        
        try {
            $event = Event::constructFrom(json_decode($payload, true));
            
        } catch (\UnexpectedValueException $e) {
            //Invalid payload;
            return response()->json([
                'message'=> 'Webhook error while parsing basic request.'
            ], 400);
        }

        $endpoint_secret = config('stripe.webhook_secret');

        if($endpoint_secret){
            $sigHeader = $request->header('Stripe-Signature'); //how do I know this header even exists
            try {
                 $event = Webhook::constructEvent(
                    $payload, $sigHeader, $endpoint_secret
                    );

            } catch (SignatureVerificationException $e) {

                return response()->json([
                    'message'=> 'Webhook error while validating signature.'
                ], 400);
            }
        }

        if( $event->type ==='checkout.session.completed') {
            $paymentSession = $event->data->object;

            if( $paymentSession->payment_status == 'paid'){
                    // Extracting our data from metadata
                $cartId = (int) $paymentSession->metadata->cart_id;
                $customerId = (int) $paymentSession->metadata->customer_id;
                $paymentMethod = PaymentMethod::from($paymentSession->metadata->payment_method);

                // We try now to create an order 
                try {
                $order = $this->checkoutService->confirm($cartId, $customerId, $paymentMethod);
                
                DB::table('ps_orders')
                    ->where('order_id', $order->id_order)
                    ->update([
                        'total_paid_real' => $paymentSession->amount_total / 100,
                        'current_state' => 2, 
                    ]);
                // $order->update([
                //     'total_paid_real' => $session->amount_total / 100, // cents → euros
                //     'current_state' => 2, // Payment accepted
                // ]);

            } catch (\RuntimeException $e) {
                // Cart already ordered — idempotency. That's OK.
                // Return 200 so Stripe doesn't retry.
                return response()->json([],200);
            }
            }
        } else {
            error_log('Unknown event or a failed checkout session'); 
            }

        return response()->json(['status' => 'ok']);
    }


    /**
     * Retrieve order details via a Stripe Checkout Session ID.
     * GET /v1/checkout/session-status?session_id=cs_test_...
     *
     * Called by the confirmation page after Stripe redirects the customer back.
     * Handles the race condition where the customer arrives before the webhook fires.
     */
    public function getSessionStatus(Request $request): JsonResponse
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return response()->json(['message' => 'Missing session_id parameter.'], 400);
        }

        // Retrieve the session from Stripe to verify it's real and read metadata
        \Stripe\Stripe::setApiKey(config('stripe.secret_key'));

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid session.'], 404);
        }

        // Check payment status
        if ($session->payment_status !== 'paid') {
            return response()->json([
                'status' => 'unpaid',
                'message' => 'Payment has not been completed.',
            ]);
        }

        // Look up the order by cart_id from metadata
        $cartId = (int) ($session->metadata->cart_id ?? 0);

        if (!$cartId) {
            return response()->json(['message' => 'No cart_id in session metadata.'], 400);
        }

        $order = \App\Models\Order::query()
            ->where('id_cart', $cartId)
            ->with('details')
            ->first();

        // Race condition: webhook hasn't fired yet
        if (!$order) {
            return response()->json([
                'status' => 'processing',
                'message' => 'Payment received, order is being created...',
            ]);
        }

        // Return order details (same shape as guestOrderDetails)
        $address = \App\Models\Address::query()->find($order->id_address_delivery);
        $customer = \App\Models\Customer::query()->find($order->id_customer);

        return response()->json([
            'status' => 'complete',
            'data' => [
                'id'                => (int) $order->id_order,
                'reference'         => $order->reference,
                'current_state'     => (int) $order->current_state,
                'payment'           => $order->payment,
                'total_products'    => (float) $order->total_products,
                'total_discounts'   => (float) $order->total_discounts,
                'total_shipping'    => (float) $order->total_shipping,
                'total_paid'        => (float) $order->total_paid,
                'date_add'          => $order->date_add,
                'customer' => $customer ? [
                    'firstname' => $customer->firstname,
                    'lastname'  => $customer->lastname,
                    'email'     => $customer->email,
                ] : null,
                'delivery_address' => $address ? [
                    'firstname'    => $address->firstname,
                    'lastname'     => $address->lastname,
                    'address1'     => $address->address1,
                    'address2'     => $address->address2,
                    'postcode'     => $address->postcode,
                    'city'         => $address->city,
                    'phone'        => $address->phone,
                    'id_country'   => (int) $address->id_country,
                ] : null,
                'details' => $order->details->map(fn ($d) => [
                    'product_id'   => (int) $d->product_id,
                    'product_name' => $d->product_name,
                    'quantity'     => (int) $d->product_quantity,
                    'unit_price'   => (float) $d->unit_price_tax_incl,
                    'total_price'  => (float) $d->total_price_tax_incl,
                ]),
            ],
        ]);
    }
}
