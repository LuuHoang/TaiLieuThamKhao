<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class CreateShareAlbumLocationMediaCommentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required|string|max:255',
            'password' => 'required|string|max:255',
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
            'token.required'  => 'messages.login_to_perform_function',
            'password.required'  => 'messages.password_is_required',
            'content.required' => 'messages.comment_is_required'
        ];
    }
}
