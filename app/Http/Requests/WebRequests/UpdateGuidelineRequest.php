<?php

namespace App\Http\Requests\WebRequests;

use App\Constants\InputType;
use App\Http\Requests\BaseRequest;

class UpdateGuidelineRequest extends BaseRequest
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
            'title'                 =>  "nullable|string|unique:guidelines,title,{$this->guidelineId},id,company_id,{$currentUser->company_id},deleted_at,NULL|max:255",
            'content'               =>  'nullable|string',
            'information'           =>  'nullable|array',
            'information.*.id'      =>  "nullable|integer|distinct|exists:guideline_information,id,guideline_id,{$this->guidelineId},deleted_at,NULL",
            'information.*.title'   =>  'required_without:information.*.id|distinct|string|max:255',
            'information.*.type'    =>  'required_without:information.*.id|numeric|in:' . InputType::TEXT . ',' . InputType::DATE . ',' . InputType::SHORT_DATE . ',' . InputType::DATE_TIME . ',' . InputType::NUMBER . ',' . InputType::EMAIL . ',' . InputType::IMAGES . ',' . InputType::VIDEOS . ',' . InputType::PDFS,
            'information.*.content' =>  'required_without:information.*.id'
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
            'title.unique'                              => 'messages.guideline_title_unique',
            'information.*.id.exists'                   => 'messages.guideline_information_exists',
            'information.*.title.required_without'      => 'messages.guideline_information_title_required',
            'information.*.title.distinct'              => 'messages.guideline_information_title_distinct',
            'information.*.type.required_without'       => 'messages.guideline_information_type_required',
            'information.*.type.in'                     => 'messages.guideline_information_type_in',
            'information.*.content.required_without'    => 'messages.guideline_information_content_required'
        ];
    }
}
