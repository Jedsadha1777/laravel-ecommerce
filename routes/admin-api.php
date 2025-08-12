<?php

use Illuminate\Support\Facades\Route;

// Admin Routes
Route::prefix('v1')->group(function () {
    // Auth
    Route::post('login', [\App\Domains\Admin\Auth\Controllers\Api\V1\AuthController::class, 'login']);
    Route::post('logout', [\App\Domains\Admin\Auth\Controllers\Api\V1\AuthController::class, 'logout']);
        
    
    Route::middleware('auth:admin-api')->group(function () {        
        // Products
        Route::apiResource('products', \App\Domains\Admin\Product\Controllers\Api\V1\ProductController::class);
    });
});


