<?php

use App\Http\Controllers\CategoryController;
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

Route::prefix('v1')->group(function (){
    route::apiResource('users','userController');
    route::apiResource('products','productController');
    route::apiResource('categories','CategoryController');
});

Route::prefix('v1')->group(function (){

route::post('categories/update/{id}','CategoryController@update');
route::post('products/update/{id}','productController@update');
route::post('users/update/{id}','userController@update');
});
