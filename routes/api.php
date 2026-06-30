<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemsController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    // Auth
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'detail']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::patch('/{id}', [ProductController::class, 'patch']);
        Route::delete('/{id}', [ProductController::class, 'delete']);

        // Semua order item yang menggunakan product ini
        Route::get('/{id}/order-items', [ProductController::class, 'orderItems']);
    });

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}', [OrderController::class, 'detail']);
        Route::put('/{id}', [OrderController::class, 'update']);
        Route::patch('/{id}', [OrderController::class, 'patch']);
        Route::delete('/{id}', [OrderController::class, 'delete']);

        // Detail item dari sebuah order
        Route::get('/{id}/items', [OrderController::class, 'orderItems']);
    });

    /*
    |--------------------------------------------------------------------------
    | Order Items
    |--------------------------------------------------------------------------
    */

    Route::prefix('order-items')->group(function () {
        Route::get('/', [OrderItemsController::class, 'index']);
        Route::post('/', [OrderItemsController::class, 'store']);
        Route::get('/{id}', [OrderItemsController::class, 'detail']);
        Route::put('/{id}', [OrderItemsController::class, 'update']);
        Route::patch('/{id}', [OrderItemsController::class, 'patch']);
        Route::delete('/{id}', [OrderItemsController::class, 'delete']);
    });

});
