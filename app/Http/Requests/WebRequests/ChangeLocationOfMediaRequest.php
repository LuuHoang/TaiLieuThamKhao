<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class ChangeLocationOfMediaRequest extends BaseRequest
{
    public function rules() {
        return [
            'location_title' => 'required|string|max:255',
        ];
    }

    public function messages() {
        return [
            'location_title.required' => 'messages.album_location_title_is_required'
        ];
    }
}
