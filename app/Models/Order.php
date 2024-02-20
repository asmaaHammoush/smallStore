<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'totalPrice',
        'userId',
        'updated_at',
        'created_at'
    ];


    public function product()
    {
        return $this->belongsToMany(Product::class,'productorder','orderId','productId','id','id')
            ->withPivot('numPieces');
    }
}
