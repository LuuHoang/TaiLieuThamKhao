<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class UpdateAppVersionRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => "required|string|max:255|unique:app_versions,name,{$this->versionId},id,deleted_at,NULL",
            'en_description' => 'nullable|string',
            'ja_description' => 'nullable|string',
            'vi_description' => 'nullable|string',
            'version_ios' => 'required|string|max:255',
            'version_android' => 'required|string|max:255',
            'active' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'messages.version_name_is_required',
            'name.unique'  => 'messages.version_name_already_exists',
            'version_ios.required' => 'messages.version_ios_is_required',
            'version_android.required' => 'messages.version_android_is_required'
        ];
    }
}
