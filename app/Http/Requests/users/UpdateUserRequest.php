<?php

namespace App\Http\Requests\users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\userController;

class UpdateUserRequest extends FormRequest
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
            'name' => 'string',
            'email' => ['email'
                ,'max:100'],
                'unique:users,email,' . $this->id,
            'password' => 'min:8',
            'photo' => 'array|max:1',
            'photo.*' => 'image
            |dimensions:width=3840,height=2160
            |mimes:gif,png,jpg,PNG,JPG,GIF
            |max:2765',
        ];
    }
}
