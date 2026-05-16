<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class RegisterRequest.
 */
class RegisterRequest extends Request
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

    public function attributes()
    {
        return [
            'name' => 'Full Name',
            'phone' => 'Mobile Number',
            'password' => 'Password',
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required|unique:users|min:10|max:10',
            'password' => 'required|max:16',
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }
}
