<?php

use App\Http\Controllers\Roles\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('index', [RoleController::class, 'index']);

route::group(
    [
        'middleware'=>['owner'],
    ],function() {
    Route::post('allowPermission/{role_id}', [RoleController::class, 'allowPermission']);
    Route::post('denyPermission/{role_id}', [RoleController::class, 'denyPermission']);
});
