<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryFilter{
    public function applyFiltersCategory( $categories, array $filters)
    {
        if (isset($filters['sort_date'])) {
            return $categories->orderBy('created_at', SORT_REGULAR, $filters['sort_date']);
        }

        if (isset($filters['sort_name'])) {
            return $categories->sortBy('name', SORT_REGULAR, $filters['sort_name']);
        }

        if (isset($filters['sort_products_number'])) {
            return  $categories->sortBy('products_count',SORT_NUMERIC, $filters['sort_products_number']);

        }
        return $categories;
    }
}
