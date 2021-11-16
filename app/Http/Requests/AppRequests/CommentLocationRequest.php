<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-04
 * Time: 16:18
 */

namespace App\Http\Requests\AppRequests;


use App\Http\Requests\BaseRequest;

class CommentLocationRequest extends BaseRequest
{

    public function rules() {
        return [
            'album_id' => 'required',
            'album_location_id' => 'required',
            'content' => 'required|string|max:255'
        ];
    }

    public function messages() {
        return [
            'album_id.required' => 'messages.album_id.required',
            'album_location_id.required' => 'messages.album_location_id.required',
            'content.required' => 'messages.content.required'
        ];
    }
}