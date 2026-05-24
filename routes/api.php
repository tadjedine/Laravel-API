<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Resources\CustomerResource;
use App\Http\Controllers\Api\V1\{CartRuleController, PostController, ProductController, CategoryController};
use App\Http\Controllers\Api\V1\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;



Route::middleware('auth:sanctum')->group(function(){
        Route::get('/user', function (Request $request) {
            return new CustomerResource($request->user());
        });
    });

    Route::prefix('v1/auth')->group(function () {
        Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
        Route::post('/login',    [LoginController::class, 'store'])->name('login');

        //Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        //Route::post('/reset-password',  [NewPasswordController::class, 'store'])->name('password.store');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
            Route::get('/me', fn (\Illuminate\Http\Request $r) => new \App\Http\Resources\CustomerResource($r->user()));
        });
    });

    Route::prefix('v1')->group(function () {

        Route::apiResource('products', ProductController::class);

        // Custom endpoints for products
        Route::get('products/{id}/images', [ProductController::class, 'getImages']);
        Route::get('products/{productId}/images/{imageId}', [ProductController::class, 'getImage']);

        // Custom endpoints for categories (must be before apiResource to avoid {id} conflict)
        Route::get('categories/root', [CategoryController::class, 'root']);
        Route::get('categories/main', [CategoryController::class, 'mainCategories']);
        // Route::get('categories/{id}/with-products', [CategoryController::class, 'showWithProducts']);
        Route::get('categories/hierarchy', [CategoryController::class, 'hierarchy']);

        Route::apiResource('categories', CategoryController::class);

        // Cart endpoints
        Route::post('cart', [CartController::class, 'index']);
        Route::get('cart/{cartId}', [CartController::class, 'show']);
        Route::post('cart/items', [CartController::class, 'store']);// add item
        Route::put('cart/items/{productId}', [CartController::class, 'update']);
        Route::delete('cart/items/{productId}', [CartController::class, 'clearItem']); // delete one line
        Route::delete('cart/{cartId}', [CartController::class, 'destroy']); // clear all items (currently using customerId as {id})

        // Cart Rule (voucher) endpoints
        Route::post('cart/rules', [CartRuleController::class, 'applyCode']);
        Route::delete('cart/rules/{code}', [CartRuleController::class, 'removeCode']);
        Route::get('cart/rules', [CartRuleController::class, 'listApplied']);

    });


require __DIR__.'/auth.php';
