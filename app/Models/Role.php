<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public function users(){
        return $this->hasMany(User::class);
    }

    public function permission(){
        return $this->belongsToMany(Permission::class,'role_has_permissions',
            'role_id','permission_id')->withPivot('Accessibility');
    }

}
