<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class ShareAlbumForEmailRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "email"     => "required|email|max:255",
            "full_name" => "required|string|max:255",
            "message"   => "nullable|string",
            "template_id" => "required|integer",
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
            'email.required'  => 'messages.email_is_required',
            'email.email'  => 'messages.email_format_is_incorrect',
            'full_name.required'  => 'messages.name_is_required',
        ];
    }
}
