<?php

namespace App\Http\Requests\WebRequests;

use App\Constants\Device;
use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
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
            'company_code' => 'required|min:6|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|max:255',
            'os' => 'nullable|in:' . Device::IOS . ',' . Device::ANDROID . ',' . Device::WEB,
            'device_token' => 'nullable|string'
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
            'company_code.required' => 'messages.company_code_is_required',
            'company_code.min' => 'messages.company_code_least_6_characters',
            'email.required'  => 'messages.email_is_required',
            'email.email'  => 'messages.email_format_is_incorrect',
            'password.required'  => 'messages.password_is_required',
            'password.min'  => 'messages.password_least_6_characters'
        ];
    }
}
