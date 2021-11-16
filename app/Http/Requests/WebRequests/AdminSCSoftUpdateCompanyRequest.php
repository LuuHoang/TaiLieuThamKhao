<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class AdminSCSoftUpdateCompanyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_name'      =>  'string|max:255',
            'company_code'      =>  "string|unique:companies,company_code,{$this->companyId},id,deleted_at,NULL|min:6|max:255",
            'address'           =>  'max:255',
            'representative'    =>  'string|max:255',
            'tax_code'          =>  'string|max:255',
            'service_id'        =>  'integer|exists:service_packages,id,deleted_at,NULL',
            'extend_id'         =>  'nullable|integer|exists:extend_packages,id,deleted_at,NULL',
            'color'             =>  'string|max:255',
            'logo'              =>  'image|mimes:jpg,jpeg,png',
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
            'company_code.unique'  => 'messages.company_code_already_exists',
            'service_id.exists'  => 'messages.service_package_does_not_exist',
            'extend_id.exists'  => 'messages.extend_package_does_not_exist',
            'logo.mimes'  => 'messages.logo_format_is_incorrect',
        ];
    }
}
