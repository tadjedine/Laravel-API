<?php

namespace App\Exceptions\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Exception;

abstract class CheckoutException extends Exception
{
    protected $code = 422;

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], $this->getCode() ?: 422);
    }
}
