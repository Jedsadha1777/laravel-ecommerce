<?php

use Illuminate\Support\Facades\Route;

// Shop Routes
Route::prefix('v1')->group(function () {
    // Auth
    Route::post('register', [\App\Domains\Shop\User\Controllers\Api\V1\AuthController::class, 'register']);
    Route::post('login', [\App\Domains\Shop\User\Controllers\Api\V1\AuthController::class, 'login']);
    Route::post('logout', [\App\Domains\Shop\User\Controllers\Api\V1\AuthController::class, 'logout']);
    
    // Catalog (Public)
    Route::get('catalog', [\App\Domains\Shop\Catalog\Controllers\Api\V1\CatalogController::class, 'index']);
    Route::get('catalog/{slug}', [\App\Domains\Shop\Catalog\Controllers\Api\V1\CatalogController::class, 'show']);
    
    Route::middleware('auth:api')->group(function () {        
        Route::get('profile', [\App\Domains\Shop\User\Controllers\Api\V1\AuthController::class, 'profile']);
        // Orders
        Route::get('orders', [\App\Domains\Shop\Order\Controllers\Api\V1\OrderController::class, 'index']);
        Route::post('orders', [\App\Domains\Shop\Order\Controllers\Api\V1\OrderController::class, 'store']);
        Route::get('orders/{orderNumber}', [\App\Domains\Shop\Order\Controllers\Api\V1\OrderController::class, 'show']);
        Route::post('orders/{orderNumber}/cancel', [\App\Domains\Shop\Order\Controllers\Api\V1\OrderController::class, 'cancel']);
    });
});
