<?php


namespace App\Http\Requests\WebRequests;


use App\Http\Requests\BaseRequest;

class CreateSampleContractPropertyRequest  extends  BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'        =>'required|string',
            'data_type'     =>'required|integer',
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
            'title.required'  => 'messages.title_is_required',
            'data_type.required'  => 'messages.data_type_is_required',
        ];
    }
}
