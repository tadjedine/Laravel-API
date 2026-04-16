<?php

namespace App\Exceptions\Cart;

class InvalidQuantityException extends CartException
{
    protected $message = 'Quantity must be at least 1';

    
}
