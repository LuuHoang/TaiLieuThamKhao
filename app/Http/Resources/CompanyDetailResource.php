<?php

namespace App\Http\Resources;

use App\Services\WebService\ContractService;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyDetailResource extends JsonResource
{
    public function toArray($request)
    {

        $contractService = app(ContractService::class);
        $contract = $contractService->getLongestContractByCompany($this->id);
        return [
            'id'                =>  $this->id,
            'company_name'      =>  $this->company_name,
            'company_code'      =>  $this->company_code,
            'address'           =>  $this->address,
            'representative'    =>  $this->representative,
            'tax_code'          =>  $this->tax_code,
            'logo_path'         =>  $this->logo_path,
            'logo_url'          =>  $this->logo_url,
            'color'             =>  $this->color,
            'service_package'   =>  new ServicePackageResource($this->servicePackage),
            'extend_package'    =>  new ExtendPackageResource($this->extendPackage),
            'company_usage'     =>  new CompanyUsageResource($this->companyUsage),
            'contract'          =>  new ContractResource($contract),
        ];
    }
}
