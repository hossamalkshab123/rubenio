<?php

use App\Http\Controllers\Api\Customer\AuthController;
use App\Http\Controllers\Api\Customer\CartController;
use App\Http\Controllers\Api\Customer\OrderController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;





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
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    
    Route::middleware(['auth:sanctum', 'customer'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);

        Route::get('/categories', [AuthController::class, 'getCategories']);
        Route::get('/categories/{categoryId}/products', [AuthController::class, 'getProductsByCategory']);
        Route::get('/offers', [AuthController::class, 'getOffers']);
        Route::post('/search-products', [AuthController::class, 'searchProducts']);
        Route::post('/favorites/toggle', [AuthController::class, 'toggleFavorite']);
        Route::get('/favorites', [AuthController::class, 'getFavorites']);
        Route::post('/cart/toggle', [CartController::class, 'toggleCart']);
        Route::get('/cart', [CartController::class, 'viewCart']);
        Route::get('/cart/summary', [CartController::class, 'orderSummary']);
        //Route::get('/orders-history', [OrderController::class, 'orderHistory']);
        Route::post('cart/checkout', [CartController::class, 'checkout']);
        Route::post('order/pay/{id}', [CartController::class, 'processPayment']);
        Route::get('view-order/{id}', [CartController::class, 'viewOrder']);
        Route::get('orders-history', [CartController::class, 'listOrders']);
        


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
    Route::post('/profile', [\App\Http\Controllers\Api\Warehouse\AuthController::class, 'login']);
    
    Route::middleware(['auth:sanctum', 'warehouse'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Warehouse\AuthController::class, 'logout']);
        // يمكنك إضافة المزيد من routes للمخزن هنا
    });
});