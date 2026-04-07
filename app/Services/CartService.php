<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use app\Repositories\CartRepository;
use Exception;

class CartService
{
    public function __construct(
        private CartRepository $cartRepository,
        private ProductService $productService
    ) {}

    public function getOrCreateCart(User $user): Cart
    {
        return $this->cartRepository->getOrCreate($user->id);
    }

    public function addToCart(User $user, int $productId, int $quantity)
    {
        if (!$this->productService->isInStock($productId, $quantity)) {
            throw new Exception('Product out of stock');
        }

        $cart = $this->getOrCreateCart($user);
        return $this->cartRepository->addItem($cart->id, $productId, $quantity);
    }

    public function updateCartItem(int $cartId, int $productId, int $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($cartId, $productId);
        }

        return $this->cartRepository->updateItem($cartId, $productId, $quantity);
    }

    public function removeFromCart(int $cartId, int $productId)
    {
        return $this->cartRepository->removeItem($cartId, $productId);
    }

    public function getCartTotal(Cart $cart): float
    {
        $total = 0;
        foreach ($cart->items as $item) {
            $total += ($item->price ?? 0) * $item->quantity;
        }
        return $total;
    }

    public function clearCart(User $user)
    {
        $cart = $this->getOrCreateCart($user);
        return $this->cartRepository->clear($cart->id);
    }
}