<?php

namespace App\Http\Requests\APIRequests;

use App\Constants\Media;
use App\Http\Requests\BaseRequest;

class UpdateImageAlbumLocationMediaRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file'          =>  'required|file|mimetypes:image/jpeg,image/png',
            'action_type'   =>  'required|numeric|in:' . Media::ACTION_UPLOAD . ',' . Media::ACTION_UPDATE
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
            'file.required'     => 'messages.not_selected_file_upload',
            'file.mimetypes'    => 'messages.file_format_is_incorrect'
        ];
    }
}
