<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('api', function (Request $request) {
    return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)
        ->by(optional($request->user())->id ?: $request->ip());
});


// Admin Routes
Route::prefix('admin')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Api\Admin\AuthController::class, 'login']);
    
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Admin\AuthController::class, 'logout']);
        // يمكنك إضافة المزيد من routes للإدارة هنا
    });
});

// Customer Routes
Route::prefix('customer')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Api\Customer\AuthController::class, 'register']);
    Route::post('/verify', [\App\Http\Controllers\Api\Customer\AuthController::class, 'verify']);
    Route::post('/complete-registration', [\App\Http\Controllers\Api\Customer\AuthController::class, 'completeRegistration']);
    Route::post('/login', [\App\Http\Controllers\Api\Customer\AuthController::class, 'login']);
    Route::post('/forgot-password', [\App\Http\Controllers\Api\Customer\AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [\App\Http\Controllers\Api\Customer\AuthController::class, 'resetPassword']);
    
    Route::middleware(['auth:sanctum', 'customer'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Customer\AuthController::class, 'logout']);
        // يمكنك إضافة المزيد من routes للعميل هنا
    });
});

// Delivery Routes
Route::prefix('delivery')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Api\Delivery\AuthController::class, 'login']);
    
    Route::middleware(['auth:sanctum', 'delivery'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Delivery\AuthController::class, 'logout']);
        // يمكنك إضافة المزيد من routes لعامل التوصيل هنا
    });
});

// Warehouse Routes
Route::prefix('warehouse')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Api\Warehouse\AuthController::class, 'login']);
    
    Route::middleware(['auth:sanctum', 'warehouse'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Warehouse\AuthController::class, 'logout']);
        // يمكنك إضافة المزيد من routes للمخزن هنا
    });
});