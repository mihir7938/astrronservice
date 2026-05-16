<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class UserRequest.
 */
class UserRequest extends Request
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
            'phone' => 'Mobile Number',
            'password' => 'Password',
        ];
    }

    public function rules()
    {
        return [
            'phone' => 'required|unique:users|min:10|max:10'
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }
}
