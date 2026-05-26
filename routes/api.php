<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// health check
Route::get('/health', fn () => response()->json([
    'status' => 'ok',
    'time'   => now()->toDateTimeString(),
]));

// auth routes
Route::prefix('auth')->middleware('throttle:auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// product routes
// Route::prefix('products')->group(function () {
//     // public
//     Route::get('/', [ProductController::class, 'index']);
//     Route::get('/{id}', [ProductController::class, 'show']);

//     // protected 
//     Route::middleware('auth:sanctum')->middleware('throttle:product-write')->group(function () {
//         Route::post('/', [ProductController::class, 'store']);
//         Route::put('/{id}', [ProductController::class, 'update']);
//         Route::delete('/{id}', [ProductController::class, 'destroy']);
//     });
// });

Route::prefix('products')->group(function () {

    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);

    Route::middleware([
        'auth:sanctum',
        'throttle:product-write',
    ])->group(function () {

        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
});