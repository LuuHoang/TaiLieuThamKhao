<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class AddLocationTypeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => "required|string|unique:location_types,title,NULL,id,deleted_at,NULL,company_id,{$this->companyId}|max:255",
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
            'title.required'  => 'messages.album_location_title_is_required',
            'title.unique'  => 'messages.album_location_title_already_exists',
        ];
    }
}
