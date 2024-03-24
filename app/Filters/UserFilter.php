<?php

namespace App\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserFilter
{
    public function applyFiltersUser($users, array $filters)
    {
        if (isset($filters['sort_date'])) {
           return $users->orderBy('created_at', SORT_REGULAR, $filters['sort_date']);
        }

        if (isset($filters['sort_name'])) {
           return $users->sortBy('name', SORT_REGULAR, $filters['sort_name']);
        }

        if (isset($filters['sort_products_number'])) {
              return  $users->sortBy('products_count',SORT_NUMERIC, $filters['sort_products_number']);

        }
        return $users;
    }

}
