<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\CheckoutTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\CheckoutService;
use App\Services\StripeService;

class StripeController extends Controller
{
    //
    public function __construct
    (   private CheckoutService $checkoutService,
        private StripeService $stripeService
    ) {}

    use CheckoutTrait;

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

    
}
