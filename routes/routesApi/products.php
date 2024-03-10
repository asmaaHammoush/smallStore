<?php

use App\Http\Controllers\productController;
use Illuminate\Support\Facades\Route;



Route::apiResource('products', 'ProductController');
Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('products')->group(function () {
            Route::post('accept/{id}', [ProductController::class, 'acceptProduct']);
            Route::post('reject/{id}', [ProductController::class, 'rejectProduct']);
        });
});
