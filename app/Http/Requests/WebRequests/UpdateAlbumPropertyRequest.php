<?php

namespace App\Http\Requests\WebRequests;

use App\Constants\AlbumPropertyType;
use App\Http\Requests\BaseRequest;

class UpdateAlbumPropertyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'nullable|string',
            'require' => 'required|boolean',
            'display' => 'required|boolean',
            'highlight' => 'required|boolean'
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
            'require.required' => 'messages.album_property_require_is_required',
            'display.required' => 'messages.album_property_display_is_required',
            'highlight.required' => 'messages.album_property_highlight_is_required',

        ];
    }
}
