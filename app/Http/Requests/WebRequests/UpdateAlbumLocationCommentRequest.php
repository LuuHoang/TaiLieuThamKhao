<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class UpdateAlbumLocationCommentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required|string|max:255'
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
            'content.required' => 'messages.comment_is_required'
        ];
    }
}
