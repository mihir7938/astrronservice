<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class ResetPasswordRequest.
 */
class ResetPasswordRequest extends Request
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
            'password' => 'Password',
        ];
    }

    public function rules()
    {
        return [
            'password' => 'required|max:16',
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }
}
