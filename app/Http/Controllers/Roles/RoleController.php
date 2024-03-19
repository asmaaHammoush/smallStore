<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\permission\PermissionRequest;
use App\Models\Role;
use App\Traits\permissions;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use permissions;
    public function index(){
        $roles =Role::with('permission')->get();
        return response()->json(['date' => $roles], 200);
    }

    public function allowPermission($role_id,PermissionRequest $request){
       return $this->updatePermissions('allow',$role_id,$request);
    }

    public function denyPermission($role_id,PermissionRequest $request){
       return $this->updatePermissions('deny',$role_id,$request);
    }
}
