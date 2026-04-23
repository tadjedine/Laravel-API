<?php

use App\Http\Controllers\Api\V1\{ PostController, ProductController, CategoryController, };
use App\Http\Controllers\Api\V1\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;



Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Route::prefix('v1')->group(function(){
    //     Route::apiResource('posts', PostController::class);
    // });
});

    Route::prefix('v1')->group(function () {
        
        Route::apiResource('posts', PostController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('categories', CategoryController::class);
        
        // Custom endpoints for products
        Route::get('products/{id}/images', [ProductController::class, 'getImages']);
        Route::get('products/{productId}/images/{imageId}', [ProductController::class, 'getImage']);
        
        // Custom endpoints for categories
        Route::get('categories/root', [CategoryController::class, 'root']);
        // Route::get('categories/{id}/with-products', [CategoryController::class, 'showWithProducts']);
        Route::get('categories/hierarchy', [CategoryController::class, 'hierarchy']);

        // Cart endpoints 
        Route::post('cart', [CartController::class, 'index']);
        Route::get('cart/{cartId}', [CartController::class, 'show']);
        Route::post('cart/items', [CartController::class, 'store']);// add item
        Route::put('cart/items/{productId}', [CartController::class, 'update']);
        Route::delete('cart/items/{productId}', [CartController::class, 'clearItem']); // delete one line 
        Route::delete('cart/{cartId}', [CartController::class, 'destroy']); // clear all items (currently using customerId as {id})
    });


require __DIR__.'/auth.php';
