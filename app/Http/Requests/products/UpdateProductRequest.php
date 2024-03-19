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
            'name' =>'string',
            'description' => ['string','max:500'],
            'price'=>'numeric',
            'quantity' =>'numeric',
            'category-id' => 'integer|exists:categories,id',
            'user_id' => ['integer','exists:users,id'],
            'photo' => 'array| min:2',
            'photo.*' => 'image
            |dimensions:width=3840,height=2160
            |mimes:gif,png,jpg,PNG,JPG,GIF
            |max:2765',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'A name is repeat',
        ];
    }
}
