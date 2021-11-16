<?php

namespace App\Http\Requests;

use App\Constants\Media;

class UploadMediasRequest extends BaseRequest
{
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

        $rules = [
            'medias' => 'required|array|max:10'
        ];

        if ($this->media_type ==  Media::TYPE_IMAGE) {
            $rules['medias.*.file'] = 'required|file|mimetypes:image/jpeg,image/png';
        }

        if ($this->media_type ==  Media::TYPE_VIDEO) {
            $rules['medias.*.file'] = 'required|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime';
        }

        $rules['medias.*.description']                      = 'nullable|string';
        $rules['medias.*.created_time']                     = 'nullable|string';
        $rules['medias.*.information']                      = 'nullable|array';
        $rules['medias.*.information.*.media_property_id']  = 'required|integer|exists:media_properties,id,deleted_at,NULL';
        $rules['medias.*.information.*.value']              = 'nullable|string|max:255';
        $rules['media_type']                                = 'required|integer|in:' . implode(',', $typeList);

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
            'medias.required'                                       => 'messages.not_selected_file_upload',
            'medias.*.file.required'                                => 'messages.not_selected_file_upload',
            'medias.*.file.mimetypes'                               => 'messages.file_format_is_incorrect',
            'medias.*.information.*.media_property_id.required'     => 'messages.media_property_id_is_required',
            'medias.*.information.*.media_property_id.exists'       => 'messages.media_property_does_not_exist',
            'media_type.required'                                   => 'messages.media_type_is_required',
            'media_type.in'                                         => 'messages.media_type_format_is_incorrect'
        ];
    }
}
