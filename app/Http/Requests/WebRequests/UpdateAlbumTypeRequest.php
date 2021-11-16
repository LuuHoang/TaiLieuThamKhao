<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class UpdateAlbumTypeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => "required|string|max:255|unique:album_types,title,{$this->albumTypeId},id,deleted_at,NULL,company_id," . app('currentUser')->company_id ,
            'description' => 'required',
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
            'description.required'  => 'messages.album_type_description_is_required',
        ];
    }
}
