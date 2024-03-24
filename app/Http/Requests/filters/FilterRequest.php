<?php

namespace App\Http\Requests\filters;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sort_name',
            'sort_date',
            'sort_products_number',
            'sort_price',
            'sort_nameCategory'
            =>Rule::in(['asc','desc'])
        ];
    }
}
