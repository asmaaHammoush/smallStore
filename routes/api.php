<?php

use App\Http\Controllers\categoryController;
use App\Http\Controllers\productController;
use App\Http\Controllers\userController;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


route::group(
    [
        'prefix'=>'v1/users'
    ],function() {
    route::post('/store',[userController::class,'store']);
    route::get('/index',[userController::class,'index']);
    route::get('/show/{id}',[userController::class,'show']);
    route::post('/update/{id}',[userController::class,'update']);
    route::post('/destroy/{id}',[userController::class,'destroy']);
});


route::group(
    [
        'prefix'=>'v1/products'
    ],function() {
    route::post('/store',[productController::class,'store']);
    route::get('/index',[productController::class,'index']);
    route::get('/show/{id}',[productController::class,'show']);
    route::post('/update/{id}',[productController::class,'update']);
    route::post('/destroy/{id}',[productController::class,'destroy']);
});

route::group(
    [
        'prefix'=>'v1/categories'
    ],function() {
    route::post('/store',[categoryController::class,'store']);
    route::get('/index',[categoryController::class,'index']);
    route::get('/show/{id}',[categoryController::class,'show']);
    route::post('/update/{id}',[categoryController::class,'update']);
    route::post('/destroy/{id}',[categoryController::class,'destroy']);
});
