<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class ImportUserRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'file' => 'required|file|mimes:xlsx'
        ];
    }

    public function messages()
    {
        return [
            'file.required'  => 'messages.not_selected_file_upload',
            'file.file'  => 'messages.file_format_is_incorrect',
            'file.mimes'  => 'messages.file_format_is_incorrect'
        ];
    }
}
