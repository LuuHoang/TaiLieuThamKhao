<?php


namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;
class CreateCompanyConfigAlbumRequest extends  BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'stamp_type'        =>'required|integer',
            'mounting_position' =>'required|integer',
            'icon'              =>'nullable|mimes:png,jpg,jpeg,svg|max:2048',
            'text'              =>'nullable|string|min:3|max:125',
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
            'stamp_type.required'  => 'messages.stamp_type_is_required',
            'mounting_position.required'  => 'messages.mounting_position_is_required',
            'link.required'  => 'messages.link_is_required',
            'link.image'    =>  'messages.file_not_is_image',
            'link.mimes'    => 'messages.file_format',
            'link.max'  => 'messages.link_is_required',
            'text.min'      =>'messages.text_min_is_3',
            'text.max'      =>'messages.text_min_is_125',
        ];
    }
}
