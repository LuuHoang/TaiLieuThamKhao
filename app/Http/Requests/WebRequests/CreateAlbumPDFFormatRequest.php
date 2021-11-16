<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class CreateAlbumPDFFormatRequest extends BaseRequest
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
            'title'         => "required|string|unique:album_pdf_formats,title,NULL,id,deleted_at,NULL,user_id,{$currentUser->id}|max:255",
            'description'   => 'nullable|string',
            'cover_page'    => 'required|string',
            'content_page_id' => 'nullable|integer',
            'content_page'  => 'required|string',
            'last_page'     => 'required|string',
            'number_images' => 'required|integer|min:0'
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
            'title.required'        => 'messages.title_is_required',
            'title.unique'          => 'messages.title_already_exists',
            'cover_page.required'   => 'messages.cover_page_is_required',
            'content_page.required' => 'messages.content_page_is_required',
            'last_page.required'   => 'messages.last_page_is_required',
        ];
    }
}
