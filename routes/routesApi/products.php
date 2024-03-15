<?php

use App\Http\Controllers\productController;
use Illuminate\Support\Facades\Route;



Route::apiResource('products', 'ProductController')->except('update');
Route::post('products/{product}', [ProductController::class, 'update']);

route::group(
    [
        'middleware'=>['auth:sanctum','admin'],
        'prefix'=>'products'
    ],function() {
            Route::post('accept/{id}', [ProductController::class, 'acceptProduct']);
            Route::post('reject/{id}', [ProductController::class, 'rejectProduct']);
        });
