<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('v1')->group(function () {
include __DIR__ . '/routesApi/auth.php';
include __DIR__ . '/routesApi/products.php';
include __DIR__ . '/routesApi/users.php';
include __DIR__ . '/routesApi/categories.php';
});

