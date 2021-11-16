<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class UpdateAlbumPDFFormatRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $currentUser = app('currentUser');
        return [
            'title'         => "nullable|string|unique:album_pdf_formats,title,{$this->formatId},id,deleted_at,NULL,user_id,{$currentUser->id}|max:255",
            'description'   => 'nullable|string',
            'cover_page'    => 'nullable|string',
            'content_page_id' => 'nullable|integer',
            'content_page'  => 'nullable|string',
            'last_page'     => 'nullable|string',
            'number_images' => 'required_with:content_page|integer|min:0'
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
            'title.unique'          => 'messages.title_already_exists',
        ];
    }
}
