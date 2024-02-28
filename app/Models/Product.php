<?php

namespace App\Models;

use App\Scopes\ProductScopes;
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
        'user_id',
        'updated_at',
        'created_at'
    ];

    protected $appends = ['created_from'];



    public function getCreatedFromAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

//    Register for global scope
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ProductScopes);
    }


    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function product()
    {
        return $this->belongsToMany(Product::class,'productorder','product_id','order_id','id','id')
            ->withPivot('numPieces');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

}
