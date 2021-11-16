<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class CreateAppVersionRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:app_versions,name,NULL,id,deleted_at,NULL',
            'en_description' => 'nullable|string',
            'ja_description' => 'nullable|string',
            'vi_description' => 'nullable|string',
            'active' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'messages.version_name_is_required',
            'name.unique'  => 'messages.version_name_already_exists'
        ];
    }
}
