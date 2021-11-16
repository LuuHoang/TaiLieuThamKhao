<?php


namespace App\Http\Requests\WebRequests;


use App\Http\Requests\BaseRequest;

class CreateSampleContractRequest extends  BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_contract'              =>'required|string|max:255',
            'description'                =>'nullable|string|max:255',
            'tags'                       =>'nullable|string',
            'sample_contract_properties' =>'nullable|array',
            'category'                   =>'nullable|integer|in:1,2,3',
            'content'                    =>'required|string',
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
            'name.required'  => 'messages.name_is_required',
            'sample_contract_properties.array' =>'messages.array'
        ];
    }
}
