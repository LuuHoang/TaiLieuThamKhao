<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class LoginAdminRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|max:255'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'  => 'messages.email_is_required',
            'email.email'  => 'messages.email_format_is_incorrect',
            'password.required'  => 'messages.password_is_required',
            'password.min'  => 'messages.password_least_6_characters'
        ];
    }
}
