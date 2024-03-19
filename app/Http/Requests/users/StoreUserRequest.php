<?php

namespace App\Http\Requests\users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required | string',
            'email' => ['required','string','email','max:100','unique:users'],
            'password' => ['required','min:8'],
            'product_id' => ['integer','exists:products,id'],
            'photo' => 'array|max:1',
            'photo.*' => 'image
            |dimensions:width=3840,height=2160
            |mimes:gif,png,jpg,PNG,JPG,GIF
            |max:2765',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'A name is required',
            'email.required' => 'An email is required',
            'password.required' => 'A password is required',
        ];
    }
}
