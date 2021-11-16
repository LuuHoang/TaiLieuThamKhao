<?php

namespace App\Http\Requests\WebRequests;

use App\Constants\InputType;
use App\Http\Requests\BaseRequest;

class CreateGuidelineRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $currentUser = app('currentUser');
        return [
            'title'                 =>  "required|string|unique:guidelines,title,NULL,id,company_id,{$currentUser->company_id},deleted_at,NULL|max:255",
            'content'               =>  'required|string',
            'information'           =>  'nullable|array',
            'information.*.title'   =>  'required|distinct|string|max:255',
            'information.*.type'    =>  'required|numeric|in:' . InputType::TEXT . ',' . InputType::DATE . ',' . InputType::SHORT_DATE . ',' . InputType::DATE_TIME . ',' . InputType::NUMBER . ',' . InputType::EMAIL . ',' . InputType::IMAGES . ',' . InputType::VIDEOS . ',' . InputType::PDFS,
            'information.*.content' =>  'required'
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
            'title.required'                    => 'messages.guideline_title_required',
            'title.unique'                      => 'messages.guideline_title_unique',
            'content.required'                  => 'messages.guideline_content_required',
            'information.*.title.required'      => 'messages.guideline_information_title_required',
            'information.*.title.distinct'      => 'messages.guideline_information_title_distinct',
            'information.*.type.required'       => 'messages.guideline_information_type_required',
            'information.*.type.in'             => 'messages.guideline_information_type_in',
            'information.*.content.required'    => 'messages.guideline_information_content_required'
        ];
    }
}
