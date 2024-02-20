<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'quantity',
        'description',
        'category_id',
        'updated_at',
        'created_at'
    ];

    protected $appends = ['created_from'];



    public function getCreatedFromAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

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
