<?php

namespace App\Exceptions\Cart;


class CartNotFoundException extends CartException
{
    public function __construct(?int $cartId = null)
    {
        $message = $cartId !== null
            ? "Cart not found (id: {$cartId})."
            : 'Cart not found.';

        parent::__construct($message, 404);
    }
}
