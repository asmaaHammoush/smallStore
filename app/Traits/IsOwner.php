<?php
namespace App\Traits;

use App\Models\User;

trait IsOwner{

    public function before(User $user)
    {
        return $user->role->name == 'Owner'?true:null;
    }

}
