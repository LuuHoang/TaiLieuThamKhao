<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class ConfigNameAlbumRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'config'            => 'nullable',
            'config.*.id'       => 'nullable|integer|exists:album_properties,id,deleted_at,NULL',
            'config.*.title'    => 'nullable|required_without:config.*.id|string|max:255',
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
            'config.*.id.exists'  => 'messages.album_property_does_not_exist',
            'config.*.title.required_without'  => 'messages.album_config_is_required',
        ];
    }
}
