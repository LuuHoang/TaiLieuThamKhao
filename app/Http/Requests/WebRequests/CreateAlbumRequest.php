<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class CreateAlbumRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'information'                                       => 'required|array',
            'information.*.album_property_id'                   => 'required|integer|exists:album_properties,id,deleted_at,NULL',
            'information.*.value'                               => 'nullable|string|max:255',
            'album_type_id'                                     => 'required|integer|exists:album_types,id,deleted_at,NULL',
            'image_path'                                        => 'nullable|string|max:255',
            'locations'                                         => 'required|array',
            'locations.*.title'                                 => 'required|string|max:255',
            'locations.*.description'                           => 'nullable|string',
            'locations.*.information'                           => 'nullable|array',
            'locations.*.information.*.location_property_id'    => 'required|integer|exists:location_properties,id,deleted_at,NULL',
            'locations.*.information.*.value'                   => 'nullable|string|max:255',
            'show_comment'                                      => 'nullable|boolean'
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
            'information.required'                                      => 'messages.album_information_is_required',
            'information.*.album_property_id.required'                  => 'messages.album_property_id_is_required',
            'information.*.album_property_id.exists'                    => 'messages.album_property_id_is_incorrect',
            'album_type_id.required'                                    => 'messages.album_type_is_required',
            'album_type_id.exists'                                      => 'messages.album_type_does_not_exist',
            'locations.required'                                        => 'messages.album_location_is_required',
            'locations.*.title.required'                                => 'messages.album_location_title_is_required',
            'locations.*.information.*.location_property_id.required'   => 'messages.location_property_id_is_required',
            'locations.*.information.*.location_property_id.exists'     => 'messages.location_property_id_is_incorrect'
        ];
    }
}
