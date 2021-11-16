<?php

namespace App\Http\Requests\WebRequests;

use App\Http\Requests\BaseRequest;

class CreateCompanyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_name'      =>  'required|string|max:255',
            'company_code'      =>  'string|unique:companies,company_code,NULL,id,deleted_at,NULL|min:6|max:255',
            'address'           =>  'required|string|max:255',
            'representative'    =>  'required|string|max:255',
            'tax_code'          =>  'required|string|max:255',
            'service_id'        =>  'required|integer|exists:service_packages,id,deleted_at,NULL',
            'extend_id'         =>  'nullable|integer|exists:extend_packages,id,deleted_at,NULL',
            'color'             =>  'required|string|max:255',
            'logo'              =>  'required|image|mimes:jpg,jpeg,png',
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
            'company_name.required'  => 'messages.company_name_is_required',
            'company_code.unique'  => 'messages.company_code_already_exists',
            'address.required'  => 'messages.address_is_required',
            'representative.required'  => 'messages.representative_is_required',
            'tax_code.required'  => 'messages.tax_code_is_required',
            'service_id.required'  => 'messages.service_package_is_required',
            'service_id.exists'  => 'messages.service_package_does_not_exist',
            'extend_id.exists'  => 'messages.extend_package_does_not_exist',
            'color.required'  => 'messages.color_is_required',
            'logo.required'  => 'messages.logo_is_required',
            'logo.mimes'  => 'messages.logo_format_is_incorrect',
        ];
    }
}
