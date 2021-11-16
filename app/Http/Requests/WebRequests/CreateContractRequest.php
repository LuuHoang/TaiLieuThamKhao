<?php


namespace App\Http\Requests\WebRequests;


use App\Constants\ContractStatus;
use App\Http\Requests\BaseRequest;

class CreateContractRequest extends  BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sample_contract_id'        =>'required|integer',
            'name_company_rental'       => 'required|string|min:3',
            'address_company_rental'    => 'required|string',
            'represent_company_rental'  => 'required|string|min:3|max:255',
            'gender_rental'             => 'required|integer|in:0,1,2',
            'phone_number_rental'       => 'required|string|min:5|max:255',

            'represent_company_hire'    => 'required|string|min:3|max:255',
            'gender_hire'               => 'required|integer|in:0,1,2',
            'phone_company_hire'        => 'required|string|min:5|max:255',

            'service_package_id'        => 'required|integer',
            'type_service_package'      => 'required|integer|in:0,1',
            'effective_date'            => 'required|date',
            'end_date'                  => 'required|date',
            'cancellation_notice_deadline'=> 'required|integer|min:1',
            'tax'                       => 'required|numeric',
            'total_price'               => 'required|numeric',
            'additional_content'        => 'required|array',
            'payment_status'            => 'required|integer|in:1,2,3',
            'deposit_money'             => 'required|numeric',
            'payment_term_all'          => 'required|integer|min:1',
            'employee_represent'        => 'required|integer',
            'contract_status'           => 'required|integer|in:' . implode(',',ContractStatus::get()),
            'date_signed'               => 'required|date',

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
            'proxy_hire_company.required'  => 'messages.proxy_hire_company_is_required',
            'phone_hire_company.required'        => 'messages.phone_hire_company_is_required',
            'gender_hire.required'               => 'messages.gender_hire_is_required',
            'name_rental_company.required'       => 'messages.name_rental_company_is_required',
            'address_rental_company.required'    => 'messages.address_rental_company_is_required',
            'proxy_rental_company.required'      => 'messages.proxy_rental_company_is_required',
            'gender_rental.required'             => 'messages.gender_rental_is_required',
            'phone_number_rental.required'       => 'messages.phone_number_rental_is_required',
            'service_package_id.required'        => 'messages.service_package_id_is_required',
            'type_service_package.required'      => 'messages.type_service_package_is_required',
            'tax.required'                       => 'messages.tax_is_required',
            'total_price.required'               => 'messages.total_price_is_required',
            'payment_status.required'            => 'messages.payment_status_is_required',
            'effective_date.required'            => 'messages.effective_date_is_required',
            'end_date.required'                  => 'messages.end_date_is_required',
            'cancellation_notice_deadline.required'=> 'messages.cancellation_notice_deadline_is_required',
            'deposit_money.required'             => 'messages.deposit_money_is_required',
            'payment_term_all.required'          => 'messages.payment_term_all_is_required',
            'proxy_employee.required'            => 'messages.proxy_employee_is_required',
            'contract_status.required'           => 'messages.contract_status_is_required',
            'date_signed.required'               => 'messages.date_signed_is_required',
        ];
    }
}
