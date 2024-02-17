<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'quantity',
        'categoryId',
        'updated_at',
        'created_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'categoryId','id');
    }

    public function product()
    {
        return $this->belongsToMany(Product::class,'productorder','productId','orderId','id','id')
            ->withPivot('numPieces');
    }

}
