<?php

namespace App\Http\Requests;

use BenSampo\Enum\Rules\EnumValue;
use App\Constants\Language;

class UpdateSettingRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image_size' => 'required|integer',
            'language' => 'required|in:' . Language::ENGLISH . ',' . Language::VIETNAMESE . ',' . Language::JAPANESE,
            'voice' => 'required|boolean',
            'comment_display' => 'nullable|boolean',
        ];
    }
    public function attributes()
    {
        return [
            'image_size' => 'Image Size',
            'language' => 'Language',
            'voice' => 'Voice',
            'comment_display' => 'Comment Display',
        ];
    }
    public function messages()
    {
        return [
            'required' => ':attribute is required!',
            'unique' => ':attribute is existed!'
        ];
    }
}
