<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Traits\IsOwner;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    use HandlesAuthorization,IsOwner;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('showAll_product');
    }


    public function view(User $user, Product $product)
    {
        return $user->hasPermissionTo('view_product') || $user->id == $product->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_product');
    }



    public function update(User $user, Product $product)
    {
        return $user->hasPermissionTo('update_product') ||
          $user->id==$product->user_id;
    }


    public function delete(User $user, Product $product)
    {
        return $user->hasPermissionTo('delete_product') ||
            $user->id==$product->user_id;
    }

    public function restore(User $user, Product $product)
    {
        //
    }

    public function forceDelete(User $user, Product $product)
    {
        //
    }
}
