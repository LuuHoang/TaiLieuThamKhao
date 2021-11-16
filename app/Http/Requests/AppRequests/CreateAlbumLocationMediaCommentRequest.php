<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-04
 * Time: 16:18
 */

namespace App\Http\Requests\AppRequests;


use App\Http\Requests\BaseRequest;

class CreateAlbumLocationMediaCommentRequest extends BaseRequest
{
    public function rules() {
        return [
            'content' => 'required|string|max:255'
        ];
    }

    public function messages() {
        return [
            'content.required' => 'messages.comment_is_required'
        ];
    }
}
