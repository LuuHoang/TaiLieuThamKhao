<?php

namespace App\Http\Requests\APIRequests;

use App\Http\Requests\BaseRequest;

class AdminCompanyUpdateUserRequest extends BaseRequest
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
            "staff_code" => "nullable|string|max:8|unique:users,staff_code,{$this->userId},id,deleted_at,NULL,company_id,{$companyId}",
            "full_name" => "string|max:255",
            "address" => "nullable|string|max:255",
            "email" => "email|max:255|unique:users,email,{$this->userId},id,deleted_at,NULL,company_id,{$companyId}",
            "password" => "string|min:6|max:255",
            "avatar_path" => "nullable|string|max:255",
            "department" => "string|max:255",
            "position" => "string|max:255",
            "role_id" => "nullable|integer|exists:user_roles,id,company_id,{$companyId},deleted_at,NULL",
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
        ];
    }
}
