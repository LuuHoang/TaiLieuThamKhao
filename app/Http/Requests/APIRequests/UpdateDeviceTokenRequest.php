<?php

namespace App\Http\Requests\APIRequests;

use App\Constants\Device;
use App\Http\Requests\BaseRequest;

class UpdateDeviceTokenRequest extends BaseRequest
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
            'os'            =>  'nullable|in:' . Device::IOS . ',' . Device::ANDROID . ',' . Device::WEB,
            'device_token'  =>  'nullable|string|max:255'
        ];
    }
}
