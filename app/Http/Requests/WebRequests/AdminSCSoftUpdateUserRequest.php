<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class AdminSCSoftUpdateUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "staff_code"    => "nullable|string|max:8|unique:users,staff_code,{$this->userId},id,deleted_at,NULL,company_id,{$this->companyId}",
            "full_name"     => "nullable|string|max:255",
            "email"         => "nullable|email|max:255|unique:users,email,{$this->userId},id,deleted_at,NULL,company_id,{$this->companyId}",
            "address"       => "nullable|string|max:255",
            "password"      => "nullable|string|min:6|max:255",
            "department"    => "nullable|string|max:255",
            "position"      => "nullable|string|max:255",
            "role_id"       => "nullable|integer|exists:user_roles,id,company_id,{$this->company_id},deleted_at,NULL",
            "user_created_id"  => "nullable|integer|exists:users,id,company_id,{$this->company_id},deleted_at,NULL",
            "extend_size"   => "nullable|integer|min:0",
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
            'role_id.exists'  => 'messages.user_role_is_incorrect',
            'user_created_id.required_if'   =>  'messages.select_management_user',
            'user_created_id.exists'    =>  'messages.management_user_is_incorrect',
        ];
    }
}
