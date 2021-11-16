<?php

namespace App\Http\Requests\APIRequests;

use App\Http\Requests\BaseRequest;

class UpdateAlbumRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'information'                           => 'required',
            'information.*.album_property_id'       => 'required|integer|exists:album_properties,id,deleted_at,NULL',
            'information.*.value'                   => 'nullable|string|max:255',
            'album_type_id'                         => 'nullable|integer|exists:album_types,id,deleted_at,NULL',
            'image_path'                            => 'nullable|string|max:255',
            'locations'                             => 'nullable',
            'locations.*.title'                     => 'required|string|max:255',
            'locations.*.comment'                   => 'nullable|string|max:255'
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
            'information.required'  => 'messages.album_information_is_required',
            'information.*.album_property_id.required'  => 'messages.album_property_id_is_required',
            'information.*.album_property_id.exists'  => 'messages.album_property_does_not_exist',
            'album_type_id.exists'  => 'messages.album_type_does_not_exist',
            'locations.*.title.required'  => 'messages.album_location_title_is_required',
        ];
    }
}
