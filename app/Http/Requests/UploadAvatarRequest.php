<?php

namespace App\Http\Requests;

class UploadAvatarRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|file|mimes:jpeg,png',
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
            'file.required'  => 'messages.avatar_is_required',
            'file.mimes'  => 'messages.avatar_format_is_incorrect',
        ];
    }
}
