<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class UpdateRoleRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|string'
        ];
    }
}
