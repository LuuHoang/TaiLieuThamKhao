<?php

namespace App\Http\Requests\APIRequests;

use App\Http\Requests\BaseRequest;

class ResetPasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'otp_code' => 'required|min:6|max:255',
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
            'otp_code.required' => 'messages.otp_code_is_required',
            'otp_code.min' => 'messages.otp_code_least_6_characters',
            'password.required'  => 'messages.password_is_required',
            'password.min'  => 'messages.password_least_6_characters'
        ];
    }
}
