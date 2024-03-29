<?php

namespace App\Models;
use App\Traits\Filter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Category extends Model
{
use Filter;
    protected $fillable = [
        'name',
        'description',
        'updated_at',
        'created_at',
        'parent_id',
    ];
    protected $appends = ['created_from','product_count'];

    public function getCreatedFromAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }


    public function scopeContainLittera($query)
    {
        return $query
            ->whereHas('product.user', function ($query) {
            $query->where('name', 'like', '%a%');
        })->with('product.user:id,name');
    }

    public function scopeCategoryWithSub($query)
    {
        return $query
            ->with('childs','image:imageable_id,photo','product.user');
    }

    public function product()
    {
        return $this->hasmany(Product::class,'category_id','id');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->select([
                'id',
                'name',
                'parent_id',
                'created_at'
            ])->with(['childs', 'product.user']);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function getProductCountAttribute(){
        return $this->product()->count();
    }

}
