<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;

class CartRepository
{
    public function getOrCreate(int $userId): Cart
    {
        return Cart::firstOrCreate(
            ['id_customer' => $userId],
            ['id_shop' => 1, 'id_currency' => 1]
        );
    }

    public function addItem(int $cartId, int $productId, int $quantity): CartItem
    {
        return CartItem::updateOrCreate(
            ['cart_id' => $cartId, 'product_id' => $productId],
            ['quantity' => $quantity]
        );
    }

    public function updateItem(int $cartId, int $productId, int $quantity): CartItem
    {
        return $this->addItem($cartId, $productId, $quantity);
    }

    public function removeItem(int $cartId, int $productId): void
    {
        CartItem::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->delete();
    }

    public function clear(int $cartId): void
    {
        CartItem::where('cart_id', $cartId)->delete();
    }
}

