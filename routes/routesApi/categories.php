<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', 'CategoryController')->except('update');
Route::post('categories/{category}', [CategoryController::class, 'update']);
