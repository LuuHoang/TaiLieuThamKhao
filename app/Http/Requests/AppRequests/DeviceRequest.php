<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-04
 * Time: 13:16
 */

namespace App\Http\Requests\AppRequests;


use App\Http\Requests\BaseRequest;

class DeviceRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'device_token' => 'required',
            'os'           => 'required'
        ];
    }

    public  function messages() {
        return [
            'device_token.required' => 'messages.device_token_is_required',
            'os_token.required' => 'messages.os_is_required'
        ];
    }
}