<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-05
 * Time: 15:22
 */

namespace App\Http\Requests\AppRequests;


use App\Http\Requests\BaseRequest;

class UpdateAlbumLocationMediaCommentRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'content' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'content.required' => 'messages.comment_is_required'
        ];
    }
}
