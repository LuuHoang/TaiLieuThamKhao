<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;
use App\Constants\InputType;

class addAlbumTypeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => "required|string|max:255|unique:album_types,title,NULL,id,deleted_at,NULL,company_id," . app('currentUser')->company_id,
            'description' => 'nullable|string',
            'album_information' => 'array',
                'album_information.*.title' => "required|string|max:255|unique:album_properties,title,NULL,id,deleted_at,NULL,company_id,",
                'album_information.*.type' => 'required|numeric|in:' . InputType::TEXT . ',' . InputType::DATE . ',' . InputType::SHORT_DATE . ',' . InputType::DATE_TIME . ',' . InputType::NUMBER . ',' . InputType::EMAIL. ',' . InputType::PDFS,
                'album_information.*.description' => "nullable|string",
                'album_information.*.require' => 'required|boolean',
                'album_information.*.display' => 'required|boolean',
                'album_information.*.highlight' => 'boolean',
            'location_information' => 'array',
                'location_information.*.title' => "required|string|max:255|unique:location_properties,title,NULL,id,deleted_at,NULL,company_id,",
                'location_information.*.type' => 'required|numeric|in:' . InputType::TEXT . ',' . InputType::DATE . ',' . InputType::SHORT_DATE . ',' . InputType::DATE_TIME . ',' . InputType::NUMBER . ',' . InputType::EMAIL,
                'location_information.*.display' => 'required|boolean',
                'location_information.*.highlight' => 'boolean',
                'location_information.*.require' => 'boolean',
                'location_information.*.description' => 'nullable|string',
            'media_information' => 'array',
                'media_information.*.title' => "required|string|max:255|unique:media_properties,title,NULL,id,deleted_at,NULL,company_id,",
                'media_information.*.type' => 'required|numeric|in:' . InputType::TEXT . ',' . InputType::DATE . ',' . InputType::SHORT_DATE . ',' . InputType::DATE_TIME . ',' . InputType::NUMBER . ',' . InputType::EMAIL,
                'media_information.*.display' => 'required|boolean',
                'media_information.*.highlight' => 'boolean',
                'media_information.*.require' => 'boolean',
                'media_information.*.description' => 'nullable|string'
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
            'title.required'  => 'messages.album_type_title_is_required',
            'title.unique'  => 'messages.album_type_title_already_exists',
        ];
    }
}
