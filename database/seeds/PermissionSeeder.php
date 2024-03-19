<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayOfPermissionNames=[
            'create_product','update_product','delete_product','showAll_product','view_product',
            'create_category','update_category','delete_category','showAll_category','view_category',
            'create_user','update_user','delete_user','showAll_user','view_user',
            'update_Accessibility'
        ];

        $permissions=collect($arrayOfPermissionNames)->map(function ($permission){
            return['name' => $permission ];
        });

        Permission::insert($permissions->toArray());
        $permissionsAll = Permission::all();

       $owner= Role::create(['name'=>'Owner']);
       $admin= Role::create(['name'=>'Admin']);
       $super_admin= Role::create(['name'=>'Super-admin']);
       $supervisor= Role::create(['name'=>'Supervisor']);

       $owner->permission()->sync($permissionsAll);
       $admin->permission()->sync($permissionsAll);
       $super_admin->permission()->sync($permissionsAll);
       $supervisor->permission()->sync($permissionsAll);
    }
}
