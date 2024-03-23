<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\IsOwner;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization,IsOwner;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('showAll_user');
    }


    public function view(User $user, User $model)
    {
        return $user->hasPermissionTo('view_user')|| $user->id == $model->id;;
    }


    public function create(User $user)
    {
        return $user->hasPermissionTo('create_user');
    }


    public function update(User $user, User $model)
    {
        return $user->hasPermissionTo('update_user') || $user->id == $model->id;
    }


    public function delete(User $user, User $model)
    {
        return $user->hasPermissionTo('delete_user');
    }

    public function restore(User $user, User $model)
    {
        //
    }


    public function forceDelete(User $user, User $model)
    {
        //
    }
}
