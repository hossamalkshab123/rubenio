<?php

use App\Http\Controllers\Api\Customer\AuthController;
use App\Http\Controllers\Api\Customer\CartController;
use App\Http\Controllers\Api\Customer\OrderController;
use App\Http\Controllers\Api\Customer\ProductController;
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


Route::prefix('customer')->group(function () {
    // Authentication Routes
    Route::post('register', [AuthController::class, 'register']);
    Route::post('verify', [AuthController::class, 'verify']);
    Route::post('complete-registration', [AuthController::class, 'completeRegistration']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    
    // Authenticated Routes
    Route::middleware(['auth:sanctum', 'customer'])->group(function () {
        // Profile Routes
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        
        // Product Routes
        Route::get('categories', [ProductController::class, 'getCategories']);
        Route::get('categories/{categoryId}/products', [ProductController::class, 'getProductsByCategory']);
        Route::get('offers', [ProductController::class, 'getOffers']);
        Route::post('search-products', [ProductController::class, 'searchProducts']);
        
        // Favorite Routes
        Route::post('favorites/toggle', [ProductController::class, 'toggleFavorite']);
        Route::get('favorites', [ProductController::class, 'getFavorites']);
        
        // Cart Routes
        Route::post('cart/toggle', [CartController::class, 'toggleCart']);
        Route::get('cart', [CartController::class, 'viewCart']);
        Route::get('cart/summary', [CartController::class, 'orderSummary']);
        
        // Order Routes
        Route::post('cart/checkout', [OrderController::class, 'checkout']);
        //Route::post('order/pay/{id}', [OrderController::class, 'processPayment']);
        Route::get('view-order/{id}', [OrderController::class, 'viewOrder']);
        Route::get('orders-history', [OrderController::class, 'listOrders']);
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

        Route::get('view-order/{id}', [OrderController::class, 'viewOrder']);
        Route::get('orders-history', [OrderController::class, 'listOrders']);
        // يمكنك إضافة المزيد من routes للمخزن هنا
    });
});