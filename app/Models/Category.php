<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Category extends Model
{

    protected $fillable = [
        'name',
        'description',
        'updated_at',
        'created_at'
    ];
    protected $appends = ['created_from'];

    public function getCreatedFromAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }


    public function product()
    {
        return $this->hasmany(Product::class,'category_id','id');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
