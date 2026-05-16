<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyCartRuleRequest;
use App\Http\Requests\RemoveCartRuleRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartRuleResource;
use App\Services\CartRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CartRuleController extends Controller
{
    public function __construct(private CartRuleService $cartRuleService) {}

    /**
     * POST /api/v1/cart/rules
     * Apply a voucher code to the customer's active cart.
     */
    public function applyCode(ApplyCartRuleRequest $request): JsonResponse
    {
        $cart = $this->cartRuleService->applyCode(
            $request->customerId(),
            $request->code(),
            $request->idLang()
        );

        return (new CartResource($cart))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * DELETE /api/v1/cart/rules/{code}
     * Remove an applied voucher code from the customer's active cart.
     */
    public function removeCode(RemoveCartRuleRequest $request): JsonResponse
    {
        $cart = $this->cartRuleService->removeCode(
            $request->customerId(),
            $request->code()
        );

        return (new CartResource($cart))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * GET /api/v1/cart/rules?cart_id=X
     * List all applied cart rules for a given cart.
     */
    public function listApplied(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'cart_id' => ['required', 'integer', 'min:1'],
        ]);

        $rules = $this->cartRuleService->getAppliedRules((int) $request->query('cart_id'));

        return CartRuleResource::collection($rules);
    }
}
