<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $permissions = Permission::pluck('name');
        foreach ($permissions as $ability) {
            Gate::define($ability, function ($auth) use ($ability) {
                $auth->load('role.permission');
                $permission = $auth->role->permission->firstWhere('name', $ability);
                if ($permission && $permission->pivot->Accessibility == 'allow') {
                    return true;
                }
                return false;
            });
        }
    }
}
