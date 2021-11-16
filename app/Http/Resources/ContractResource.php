<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource  extends  JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    =>    $this->id,
            'sample_contract'       =>    new SampleContractResource($this->sampleContract),
            'company'	            =>    new CompanyResource($this->company),
            'contract_code'         =>    $this->contract_code,
            'represent_company_hire'=>    $this->represent_company_hire,
            'phone_company_hire'    =>    $this->phone_company_hire,
            'gender_hire'           =>    $this->gender_hire,
            'name_company_rental'   =>    $this->name_company_rental,
            'address_company_rental'=>    $this->address_company_rental,
            'represent_company_rental'=>  $this->represent_company_rental,
            'gender_rental'         =>    $this->gender_rental,
            'phone_number_rental'   =>    $this->phone_number_rental,
            'additional_content'    =>    AddtionalContentResource::collection($this->sampleContractProperty),
            'service_package'       =>    new ServicePackageResource($this->servicePackage),
            'type_service_package'  =>    $this->type_service_package,
            'extend_package'        =>    new ExtendPackageResource($this->extendPackage),
            'tax'                   =>    $this->tax,
            'total_price'           =>    $this->total_price,
            'payment_status'        =>    $this->payment_status,
            'contract_status'       =>    $this->contract_status,
            'effective_date'        =>    $this->effective_date,
            'end_date'              =>    $this->end_date,
            'cancellation_notice_deadline'=>    $this->cancellation_notice_deadline,
            'deposit_money'         =>    $this->deposit_money,
            'payment_term_all'      =>    $this->payment_term_all,
            'employee_represent'    =>    new ListAdminResource($this->admin),
            'date_signed'           => $this->date_signed,
            'created_by'           =>    $this->created_by,
            'updated_by'           =>    $this->updated_by,
            'created_at'           =>    $this->created_at,
            'deleted_at'           =>    $this->deleted_at,
        ];
    }
}
