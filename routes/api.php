<?php

use App\Http\Controllers\Api\V1\{
    PostController,
    ProductController,
    CategoryController,
    };
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
    });



require __DIR__.'/auth.php';
