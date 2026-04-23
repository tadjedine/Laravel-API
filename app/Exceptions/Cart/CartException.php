<?php

namespace App\Exceptions\Cart;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class CartException extends Exception
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
