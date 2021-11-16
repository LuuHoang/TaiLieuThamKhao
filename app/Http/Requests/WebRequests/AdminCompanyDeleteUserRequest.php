<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class AdminCompanyDeleteUserRequest extends BaseRequest
{
    public function rules()
    {
        $companyId = app('currentUser')->company_id;
        return [
            "user_target_id" => "required|integer|exists:users,id,deleted_at,NULL,company_id,{$companyId}"
        ];
    }

    public function messages()
    {
        return [
            'user_target_id.exists'  => 'messages.user_does_not_exist',
        ];
    }
}
