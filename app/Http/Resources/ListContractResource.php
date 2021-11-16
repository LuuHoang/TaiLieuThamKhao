<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ListContractResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                    =>  $this->id,
            'company_name'          =>  $this->company_name,
            'contract_code'         =>  $this->contract_code ?? null,
            'name_company_rental'   =>  $this->name_company_rental,
            'service_package'       =>  new ServicePackageResource($this->servicePackage),
            'template_contract'     =>  new SampleContractResource($this->sampleContract),
            'service_name'          =>  $this->title,
            'effective_date'        =>  $this->effective_date,
            'end_date'              =>  $this->end_date,
            'date_signed'           =>  $this->date_signed,
            'represent_company_hire'=>  $this->represent_company_hire,
            'phone_company_hire'=>  $this->phone_company_hire,
            'payment_status'=>  $this->payment_status,
            'contract_status'=>  $this->contract_status,
            'created_at'=>  $this->created_at,
            'updated_at'=>  $this->updated_at,
            'represent_company_rental'=>  $this->represent_company_rental,
        ];
    }
}
