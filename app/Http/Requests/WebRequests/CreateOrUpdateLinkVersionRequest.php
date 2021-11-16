<?php


namespace App\Http\Requests\WebRequests;


use App\Http\Requests\BaseRequest;

class CreateOrUpdateLinkVersionRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "ios"       => "required|url|max:255",
            "android"   => "required|url|max:255"
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
            "ios.required"      => "messages.ios_is_required",
            "android.required"  => "messages.android_is_required",
            "ios.url"           => "messages.ios_is_url",
            "android.url"       => "messages.android_is_url"
        ];
    }
}
