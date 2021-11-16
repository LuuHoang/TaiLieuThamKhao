<?php

namespace App\Http\Requests\WebRequests;

use App\Constants\InputType;
use App\Http\Requests\BaseRequest;

class AddAlbumPropertyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => "required|string|unique:album_properties,title,NULL,id,deleted_at,NULL,company_id,{$this->companyId}|max:255",
            'type' => 'required|numeric|in:' . InputType::TEXT . ',' . InputType::DATE . ',' . InputType::SHORT_DATE . ',' . InputType::DATE_TIME . ',' . InputType::NUMBER . ',' . InputType::EMAIL . ',' . InputType::PDFS,
            'description' => "nullable|string",
            'require' => 'required|boolean',
            'display' => 'required|boolean',
            'highlight' => 'boolean'
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
            'title.required' => 'messages.album_property_title_is_required',
            'title.unique' => 'messages.album_property_title_already_exists',
            'type.required' => 'messages.album_property_type_is_required',
            'type.in' => 'messages.album_property_type_is_incorrect',
        ];
    }
}
