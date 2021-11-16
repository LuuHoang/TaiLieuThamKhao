<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class UpdateCompanyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "company_name"  =>  "string|max:255|unique:companies,company_name,{$this->companyId},id,deleted_at,NULL",
            "address"       =>  "string|max:255",
            "color"         =>  "string|max:255",
            "logo"          =>  "image|mimes:jpg,jpeg,png"
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
            'company_name.unique'  => 'messages.company_name_does_not_exist',
            'logo.mimes'  => 'messages.logo_format_is_incorrect',
        ];
    }
}
