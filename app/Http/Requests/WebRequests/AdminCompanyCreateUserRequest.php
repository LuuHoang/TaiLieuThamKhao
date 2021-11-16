<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class AdminCompanyCreateUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $companyId = app('currentUser')->company_id;
        return [
            "staff_code"        => "nullable|string|max:8|unique:users,staff_code,NULL,id,deleted_at,NULL,company_id,{$companyId}",
            "full_name"         => "required|string|max:255",
            "email"             => "required|email|max:255|unique:users,email,NULL,id,deleted_at,NULL,company_id,{$companyId}",
            "address"           => "nullable|string|max:255",
            "password"          => "required|string|min:6|max:255",
            "department"        => "nullable|string|max:255",
            "position"          => "nullable|string|max:255",
            "role_id"           => "required|integer|exists:user_roles,id,company_id,{$companyId},deleted_at,NULL",
            "user_created_id"   => "nullable|integer|exists:users,id,company_id,{$companyId},deleted_at,NULL",
            "avatar_path"       => "nullable|string|max:255"
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
            'staff_code.required'  => 'messages.staff_code_is_required',
            'staff_code.max'  => 'messages.staff_code_must_8_characters',
            'staff_code.unique'  => 'messages.staff_code_already_exists',
            'full_name.required'  => 'messages.name_is_required',
            'email.required'  => 'messages.email_is_required',
            'email.unique'  => 'messages.email_already_exists',
            'address.required'  => 'messages.address_is_required',
            'password.required'  => 'messages.password_is_required',
            'password.min'  => 'messages.password_least_6_characters',
            'department.required'  => 'messages.department_is_required',
            'position.required'  => 'messages.position_is_required',
            'role_id.required'  => 'messages.user_role_is_required',
            'role_id.exists'  => 'messages.user_role_is_incorrect',
            'user_created_id.exists'    =>  'messages.management_user_is_incorrect',
            'avatar_path.required'  => 'messages.avatar_is_required',
        ];
    }
}
