<?php
namespace App\Traits;

trait Filter{

    public function scopeFilter($query, array $filters){
        if (isset($filters['sort_date'])) {
            $query = $query->orderBy('created_at', $filters['sort_date']);
        }

        if (isset($filters['sort_name'])) {
            $query = $query->orderBy('name', $filters['sort_name']);
        }

        if (isset($filters['sort_products_number'])) {
            $query = $query->withCount('product')->orderBy('product_count', $filters['sort_products_number']);
        }

        return $query;
    }
}
