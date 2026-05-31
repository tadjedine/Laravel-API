<?php

namespace App\Exceptions\Cart;

use Exception;

class CartEmptyException extends CartException
{
    protected $message = 'Cart must contain at least one product';
}
