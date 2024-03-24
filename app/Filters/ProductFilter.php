<?php

namespace App\Filters;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductFilter{

    public function applyFiltersProduct($products, array $filters)
    {
        if (isset($filters['sort_date'])) {
            return $products->orderBy('created_at', SORT_REGULAR, $filters['sort_date']);
        }

        if (isset($filters['sort_name'])) {
            return $products->sortBy('name', SORT_REGULAR, $filters['sort_name']);
        }

        if (isset($filters['sort_price'])) {
            return $products->sortBy('price', SORT_REGULAR, $filters['sort_price']);
        }

        if (isset($filters['sort_status'])) {
            $products = $products->sortBy('status',SORT_STRING);
        }

        if (isset($filters['sort_nameCategory'])) {
            $products = $products->sortBy(function ($product) {
                return $product->category->name;
            }, SORT_REGULAR);

            if ($filters['sort_nameCategory'] === 'desc') {
                $products = $products->reverse();
            }
        }

        if (isset($filters['sort_products_number'])) {
            $products = $products->sortBy(function ($product) {
                return $product->category->products_count;
            }, SORT_REGULAR);

            if ($filters['sort_products_number'] === 'desc') {
                $products = $products->reverse();
            }
        }

        return $products;
    }

}
