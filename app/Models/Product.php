<?php

namespace App\Models;

use App\Scopes\ProductScopes;
use App\Traits\Filter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Filter;
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

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['sort_date'])) {
            return $query->orderBy('created_at', $filters['sort_date']);
        }

        if (isset($filters['sort_name'])) {
            return $query->orderBy('name', $filters['sort_name']);
        }

        if (isset($filters['sort_price'])) {
            return $query->orderBy('price', $filters['sort_price']);
        }

        if (isset($filters['sort_status'])) {
            $query = $query->sortBy('status',SORT_STRING);
        }

        if (isset($filters['sort_nameCategory'])) {
            $query = $query->with(['category' => function ($query) use ($filters) {
                $query->orderBy('name', $filters['sort_nameCategory']);
            }]);

            if ($filters['sort_nameCategory'] === 'desc') {
                $query = $query->orderBy('id', 'desc');
            }
        }

        if (isset($filters['sort_products_number'])) {
            $query->joinSub(
                Product::select('category_id')
                    ->selectRaw('count(*) as product_count')
                    ->groupBy('category_id'),
                'products_count',
                function ($join) {
                    $join->on('products.category_id', '=', 'products_count.category_id');
                }
            )->orderBy('product_count', $filters['sort_products_number']);
        }

        return $query;
    }

}
