<?php

namespace App\Http\Requests\WebRequests;

use App\Constants\InputType;
use App\Http\Requests\BaseRequest;

class AddMediaPropertyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => "required|string|max:255|unique:media_properties,title,NULL,id,deleted_at,NULL,album_type_id," . $this->albumTypeId,
            'type' => 'required|numeric|in:' . InputType::TEXT . ',' . InputType::DATE . ',' . InputType::SHORT_DATE . ',' . InputType::DATE_TIME . ',' . InputType::NUMBER . ',' . InputType::EMAIL,
            'display' => 'required|boolean',
            'highlight' => 'boolean',
            'require' => 'boolean',
            'description' => 'nullable|string'
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
            'title.required'  => 'messages.media_property_title_is_required',
            'title.unique'  => 'messages.media_property_title_already_exists',
            'type.required'  => 'messages.media_property_type_is_required',
            'type.in'  => 'messages.media_property_type_is_incorrect',
        ];
    }
}
