<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\CartItemRequest;
use App\Http\Requests\GetOrCreateCartRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index(GetOrCreateCartRequest $request)
    {
        $cart = $this->cartService->getOrCreateCart(
            $request->customerId(),
            $request->context()
        );

        return new CartResource($cart);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddCartItemRequest $request)
    {
        $customerId = $request->customerId();
        $productId = $request->productId();
        $quantity = $request->quantity();
        $context = $request->context();

        $addedCart = $this->cartService->addItem($customerId, $productId, $quantity, $context);

        return (new CartResource($addedCart))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartItemRequest $request, string $id)
    {
        $cart = $this->cartService->updateItemQuantity(
            $request->customerId(),
            (int) $id,
            $request->quantity(),
            $request->context()
        );

        return new CartResource($cart);
    }

    /**
     * Delete the whole cart content
     */
    public function destroy(string $id)
    {
        $cart = $this->cartService->getCart((int) $id);

        if (! $cart) {
            return response()->json([
                'message' => 'Cart not found.',
            ], 404);
        }

        // keeping the cart row, deleting only cart content
        $cart->products()->delete();

        //  updating timestamp
        $cart->date_upd = now();
        $cart->save();

        return response()->noContent();
    }

    /**
     * Delete a single cart line
     */
    public function clearItem(CartItemRequest $request)
    {
        $context = $request->context();

        $cart = $this->cartService->removeItem(
            $request->customerId(),
            $request->productId(),
            $request->context(),
        );

        return new CartResource($cart);
    }
}
