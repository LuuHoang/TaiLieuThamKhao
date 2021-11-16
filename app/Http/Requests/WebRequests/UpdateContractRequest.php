<?php


namespace App\Http\Requests\WebRequests;


use App\Constants\ContractStatus;
use App\Http\Requests\BaseRequest;

class UpdateContractRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sample_contract_id'        => 'required|integer',
            'name_company_rental'       => 'required|string|min:3',
            'address_company_rental'    => 'required|string',
            'represent_company_rental'  => 'required|string|min:3|max:255',
            'gender_rental'             => 'nullable|integer|in:0,1,2',
            'phone_number_rental'       => 'nullable|string|min:5|max:255',

            'represent_company_hire'    => 'nullable|string|min:3|max:255',
            'gender_hire'               => 'nullable|integer|in:0,1,2',
            'phone_company_hire'        => 'nullable|string|min:5|max:255',

            'service_package_id'        => 'required|integer',
            'type_service_package'      => 'nullable|integer|in:0,1',
            'effective_date'            => 'required|date',
            'end_date'                  => 'required|date',
            'cancellation_notice_deadline'=> 'nullable|integer|min:1',
            'tax'                       => 'nullable|numeric',
            'total_price'               => 'nullable|numeric',
            'additional_content'        => 'nullable|array',
            'payment_status'            => 'nullable|integer|in:1,2,3',
            'deposit_money'             => 'nullable|numeric',
            'payment_term_all'          => 'nullable|integer|min:1',
            'employee_represent'        => 'required|integer',
            'contract_status'           => 'nullable|integer|in:' . implode(',',ContractStatus::get()),
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
            'sample_contract_id.required'  => 'messages.sample_contract_id_is_required',
            'name_company_rental.required'        => 'messages.name_company_rental_is_required',
            'address_company_rental.required'     => 'messages.address_company_rental_is_required',
            'represent_company_rental.required'   => 'messages.represent_company_rental_is_required',

            'service_package_id.required'  => 'messages.service_package_id_is_required',
            'effective_date.required'        => 'messages.effective_date_is_required',
            'end_date.required'     => 'messages.end_date_is_required',
            'employee_represent.required'   => 'messages.employee_represent_is_required',
            'date_signed.required'  => 'messages.date_signed_is_required',

        ];
    }
}
