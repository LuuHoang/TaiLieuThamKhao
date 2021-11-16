<?php

namespace App\Http\Requests\APIRequests;

use App\Http\Requests\BaseRequest;

class ChangePasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => 'required|min:6|max:255',
            'password' => 'required|confirmed|min:6|max:255',
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
            'old_password.required'  => 'messages.old_password_is_required',
            'old_password.min'  => 'messages.old_password_least_6_characters',
            'password.required'  => 'messages.password_is_required',
            'password.min'  => 'messages.password_least_6_characters'
        ];
    }
}
