<?php
namespace App\Traits;

use App\Http\Requests\permission\PermissionRequest;
use App\Models\Role;
use Illuminate\Http\Request;

trait permissions{
    use HttpResponses;

    public function updatePermissions($access, $role_id, PermissionRequest $request)
    {
        $role = Role::findOrFail($role_id);
        $rolePermission = $role->permission();
        $permission_ids = $request->permission_id;
        $rolePermission->whereIn('permission_id', $permission_ids)->update([
            'Accessibility' => $access
        ]);
        if ($rolePermission->whereIn('permission_id', $permission_ids)->count() > 0) {
            return $this->responseSuccess('Updated successfully', 200);
        }
        return $this->responseError('No validity found', 400);
    }
}
