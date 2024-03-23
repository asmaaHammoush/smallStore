<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use App\Traits\IsOwner;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization,IsOwner;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('showAll_category');
    }


    public function view(User $user, Category $category)
    {
        return $user->hasPermissionTo('view_category');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_category');
    }


    public function update(User $user, Category $category)
    {
        return $user->hasPermissionTo('update_category');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo('delete_category');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Category  $category
     * @return mixed
     */
    public function restore(User $user, Category $category)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Category  $category
     * @return mixed
     */
    public function forceDelete(User $user, Category $category)
    {
        //
    }
}
