<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class ForgotPasswordRequest.
 */
class ForgotPasswordRequest extends Request
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
            'email' => 'Email',
        ];
    }

    public function rules()
    {
        return [
            'email' => 'required|email|max:155',
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }
}
