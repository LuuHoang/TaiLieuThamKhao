<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class UpdateAlbumLocationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "description"                               => "nullable|string",
            'latest_updated_at'                         => 'nullable|string',
            "information"                               => "nullable|array",
            "information.*.location_property_id"        => "required|integer|exists:location_properties,id,deleted_at,NULL",
            "information.*.value"                       => "nullable|string|max:255",
            "medias"                                    => "nullable|array",
            "medias.*.id"                               => "required|integer|exists:album_location_medias,id,album_location_id,{$this->locationId},deleted_at,NULL",
            "medias.*.description"                      => "nullable|string",
            "medias.*.created_time"                     => "nullable|string",
            "medias.*.information"                      => "nullable|array",
            "medias.*.information.*.media_property_id"  => "required|integer|exists:media_properties,id,deleted_at,NULL",
            "medias.*.information.*.value"              => "nullable|string|max:255"
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
            'information.*.location_property_id.required'           => 'messages.location_property_id_is_required',
            'information.*.location_property_id.exists'             => 'messages.location_property_does_not_exist',
            'medias.*.id.exists'                                    => 'messages.media_does_not_exist',
            'medias.*.information.*.media_property_id.required'     => 'messages.media_property_id_is_required',
            'medias.*.information.*.media_property_id.exists'       => 'messages.media_property_id_is_incorrect',
        ];
    }
}
