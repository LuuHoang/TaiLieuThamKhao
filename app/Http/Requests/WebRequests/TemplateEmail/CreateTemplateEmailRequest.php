<?php

namespace App\Http\Requests\WebRequests\TemplateEmail;

use App\Http\Requests\BaseRequest;

class CreateTemplateEmailRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'subject' => 'required|string',
            'default' => 'required|bool',
            'cc_list' => 'nullable|array',
            'cc_list.*' => 'nullable|email',
            'bcc_list' => 'nullable|array',
            'bcc_list.*' => 'nullable|email',
        ];
    }
}
