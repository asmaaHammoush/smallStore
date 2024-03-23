<?php

namespace App\Models;

use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens,HttpResponses;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'product_id',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['created_from'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getCreatedFromAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function product()
    {
        return $this->hasMany(Product::class,'user_id','id');
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function hasPermissionTo($permission){
        $auth=Auth::user();
        $permission = $auth->role->permission->firstWhere('name', $permission);
        if ( $permission->pivot->Accessibility == 'allow') {
            return true;
        }else
        return false;
    }
}
