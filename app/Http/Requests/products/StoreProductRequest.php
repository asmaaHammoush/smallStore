<?php

namespace App\Http\Requests\products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' =>['required','string','unique:products,name'],
            'description' => ['required','string','max:500'],
            'price'=>['required','numeric'],
            'quantity' =>['required','numeric'],
            'category_id' => ['required','integer','exists:categories,id'],
        ];
    }
}
