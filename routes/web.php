<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeliveryController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;





Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware(['auth:admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // المسار الصحيح لفئة Categories
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('warehouses', WarehouseController::class);
        Route::resource('deliveries', DeliveryController::class);
        Route::resource('coupons', CouponController::class);
    });
});