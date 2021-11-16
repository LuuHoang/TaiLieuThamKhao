<?php

namespace App\Http\Requests\APIRequests;

use App\Constants\Media;
use App\Http\Requests\BaseRequest;

class UpdateAlbumLocationMediaRequest extends BaseRequest
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
        $typeList = [
            Media::TYPE_IMAGE,
            Media::TYPE_VIDEO
        ];

        $rules = [];

        if ($this->media_type ==  Media::TYPE_IMAGE) {
            $rules['file'] = 'nullable|file|mimetypes:image/jpeg,image/png';
        }

        if ($this->media_type ==  Media::TYPE_VIDEO) {
            $rules['file'] = 'nullable|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime';
        }

        $rules['description']                       = 'nullable|string';
        $rules['latest_updated_at']                 = 'nullable|string';
        $rules['created_time']                      = 'nullable|string';
        $rules['information']                       = 'nullable|array';
        $rules['information.*.media_property_id']   = 'required|integer|exists:media_properties,id,deleted_at,NULL';
        $rules['information.*.value']               = 'nullable|string|max:255';
        $rules['media_type']                        = 'required_with:file|integer|in:' . implode(',', $typeList);

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'file.mimetypes'                               => 'messages.file_format_is_incorrect',
            'created_time.date_format'                     => 'messages.date_time_format_is_incorrect',
            'information.*.media_property_id.required'     => 'messages.media_property_id_is_required',
            'information.*.media_property_id.exists'       => 'messages.media_property_does_not_exist',
            'media_type.required_with'                     => 'messages.media_type_is_required',
            'media_type.in'                                => 'messages.media_type_format_is_incorrect'
        ];
    }
}
