<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\ClearCartItemRequest;
use App\Http\Requests\GetOrCreateCartRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\Request;

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
    public function update(Request $request, string $id)
    {
        // To add the logic of updating a cart ( product quantity, other infos ...idk)
    }

    /**
     * Delete the whole cart content
     */
    public function destroy(string $id)
    {
        // $cart= $this->cartService->findLatestOpenCartByCustomer($id);

        // if(! $cart){
        //     return response()->json(['message' => 'Product not found'], 404);
        // }

        // $customerId = $cart->customer()->id_customer;
        // $productId = $cart->products()->id_customer;

        $this->cartService->clearItems($id);

        return response()->noContent();
    }

    // Delete a single cart line
    public function clearItem(ClearCartItemRequest $request)
    {
        $context = $request->context();

        $cart = $this->cartService->removeItem(
            (int) $request['id_customer'],
            (int) $request['id_product'],
            $context
        );

        return new CartResource($cart);
    }
}
