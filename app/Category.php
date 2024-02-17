<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Category extends Model
{

    protected $fillable = [
        'name',
        'updated_at',
        'created_at'
    ];


    public function product()
    {
        return $this->hasmany(Product::class,'categoryId','id');
    }
}
