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
            error_log('Unknown event or a failed checkout session'); // don't know if there's even a "checkout.session.failed" event type.
            }

        return response()->json(['status' => 'ok']);
    }

    
}
