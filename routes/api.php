<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Employee\OrderManagementController;
use App\Http\Controllers\Api\Admin\StatsController;
use App\Http\Controllers\Api\Admin\CategoryController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    ROute::post('/orders', [OrderController::class, 'store']);
    ROute::get('/orders', [OrderController::class, 'index']);
    ROute::get('/orders/{orderid}', [OrderController::class, 'show']);
    ROute::post('/orders/cancel/{cancleid}', [OrderController::class, 'cancel']);
});

Route::middleware(['auth:api', 'role:employee'])->group(function () {
    Route::put('/employee/orders/{id}/prepare', [OrderManagementController::class, 'prepare']);
    Route::put('/employee/orders/{id}/deliver', [OrderManagementController::class, 'deliver']);
});
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/admin/stats', [StatsController::class, 'index']);
});
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/admin/categories', [CategoryController::class, 'index']);
    Route::post('/admin/categories', [CategoryController::class, 'store']);
    Route::put('/admin/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy']);

    Route::get('/admin/products', [AdminProductController::class, 'index']);
    Route::post('/admin/products', [AdminProductController::class, 'store']);
    Route::put('/admin/products/{id}', [AdminProductController::class, 'update']);
    Route::delete('/admin/products/{id}', [AdminProductController::class, 'destroy']);
});