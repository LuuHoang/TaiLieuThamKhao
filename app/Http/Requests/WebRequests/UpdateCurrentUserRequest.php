<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class UpdateCurrentUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $currentUser = app('currentUser');
        return [
            "staff_code"    => "nullable|string|max:8|unique:users,staff_code,{$currentUser->id},id,deleted_at,NULL,company_id,{$currentUser->company_id}",
            "full_name"     => "nullable|string|max:255",
            "address"       => "nullable|string|max:255",
            "email"         => "nullable|email|max:255|unique:users,email,{$currentUser->id},id,deleted_at,NULL,company_id,{$currentUser->company_id}",
            "department"    => "nullable|string|max:255",
            "position"      => "nullable|string|max:255",
            "avatar_path"   => "nullable|string|max:255"
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
            'staff_code.max'  => 'messages.staff_code_must_8_characters',
            'staff_code.unique'  => 'messages.staff_code_already_exists',
            'email.unique'  => 'messages.email_already_exists',
            'password.min'  => 'messages.password_least_6_characters',
        ];
    }
}
