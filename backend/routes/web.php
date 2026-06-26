<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [UserController::class, 'show'])
    ->name('users.show')
    ->where('id', '[0-9]+');
Route::get('/users/{username}/{email}', [UserController::class, 'getUsernameEmail'])
    ->name('users.getUsernameEmail');


//admin
Route::middleware(['auth', 'admin', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Pages (Blade views)
    Route::get('/categories', function () {
        return view('admin.categories');
    })->name('categories.index');

    Route::get('/products', function () {
        return view('admin.products');
    })->name('products.index');

    Route::get('/orders', function () {
        return view('admin.orders');
    })->name('orders.index');

    Route::get('/orders/{order}', function (\App\Models\Order $order) {
        return view('admin.order-show', compact('order'));
    })->name('orders.show');

    Route::get('/users', function () {
        return view('admin.users');
    })->name('users.index');

    Route::get('/users/{user}', function (\App\Models\User $user) {
        return view('admin.user-show', compact('user'));
    })->name('users.show');

    // API Routes for CRUD operations
    Route::apiResource('api/categories', CategoryController::class);
    Route::post('api/categories/list', [CategoryController::class, 'list'])->name('api.categories.list');
    Route::apiResource('api/products', ProductController::class);
    Route::apiResource('api/orders', OrderController::class)->only(['index', 'show']);
    Route::put('api/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('api.orders.status');
    Route::apiResource('api/users', AdminUserController::class)->except(['store']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});

// Regular user routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return 'Admin Dashboard';
    });
});
