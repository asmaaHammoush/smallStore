<?php

namespace App\Http\Requests\products;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' =>'string|unique:products,name'.$this->id,
            'description' => ['string','max:500'],
            'price'=>'numeric',
            'quantity' =>'numeric',
            'category-id' => 'integer|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'A name is repeat',
        ];
    }
}
