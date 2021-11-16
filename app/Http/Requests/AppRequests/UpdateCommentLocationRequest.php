<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-05
 * Time: 11:12
 */

namespace App\Http\Requests\AppRequests;


use App\Http\Requests\BaseRequest;

class UpdateCommentLocationRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'id' => 'required|integer|exists:album_comments,id,deleted_at,NULL',
            'content' => 'required|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'messages.comment_id_need_to_required',
            'id.exists' => 'messages.comment_id_does_not_exists',
            'content.required' => 'messages.content_need_to_required'
        ];
    }

}