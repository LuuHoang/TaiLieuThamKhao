<?php

namespace App\Http\Requests\AppRequests;

use App\Http\Requests\BaseRequest;

class CreateAlbumLocationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "locations"                                         => "required|array",
            "locations.*.title"                                 => "required|string|unique:album_locations,title,NULL,id,deleted_at,NULL,album_id,{$this->albumId}|max:255",
            "locations.*.description"                           => "nullable|string",
            "locations.*.information"                           => "nullable|array",
            "locations.*.information.*.location_property_id"    => "required|integer|exists:location_properties,id,deleted_at,NULL",
            "locations.*.information.*.value"                   => "nullable|string|max:255"
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
            'locations.*.title.required'                                => 'messages.album_location_title_is_required',
            'locations.*.title.unique'                                  => 'messages.album_location_title_already_exists',
            'locations.*.information.*.location_property_id.required'   => 'messages.location_property_id_is_required',
            'locations.*.information.*.location_property_id.exists'     => 'messages.location_property_id_is_incorrect'
        ];
    }
}
