<?php

namespace App\Http\Requests\WebRequests;

use App\Constants\InputType;
use App\Http\Requests\BaseRequest;

class AddLocationPropertyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => "required|string|unique:location_properties,title,NULL,id,deleted_at,NULL,company_id,{$this->companyId}|max:255",
            'type' => 'required|numeric|in:' . InputType::TEXT . ',' . InputType::DATE . ',' . InputType::SHORT_DATE . ',' . InputType::DATE_TIME . ',' . InputType::NUMBER . ',' . InputType::EMAIL,
            'display' => 'required|boolean',
            'description' => 'nullable|string',
            'highlight' => 'boolean',
            'require' => 'boolean',
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
            'title.required'  => 'messages.location_property_title_is_required',
            'title.unique'  => 'messages.location_property_title_already_exists',
            'type.required'  => 'messages.location_property_type_is_required',
            'type.in'  => 'messages.location_property_type_is_incorrect',
        ];
    }
}
