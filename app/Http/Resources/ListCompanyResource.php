<?php

namespace App\Http\Resources;

use App\Services\CommonService;
use App\Services\HandleResourceService;
use App\Services\WebService\ContractService;
use Illuminate\Http\Resources\Json\JsonResource;

class ListCompanyResource extends JsonResource
{
    public function toArray($request): array
    {
        $handleResourceService = app(HandleResourceService::class);
        $commonService = app(CommonService::class);
        $servicePackage = $this->servicePackage;
        $extendPackage = $this->extendPackage;
        $companyUsage = $this->companyUsage;
        $contractService = app(ContractService::class);
        $contract = $contractService->getLongestContractByCompany($this->id);
        return [
            'id'                =>  $this->id,
            'company_name'      =>  $this->company_name,
            'company_code'      =>  $this->company_code,
            'color'             =>  $this->color,
            'logo_path'         =>  $this->logo_path,
            'logo_url'          =>  $this->logo_url,
            'address'           =>  $this->address,
            'service_id'        =>  $this->service_id,
            'service_package'   =>  $servicePackage->title,
            'extend_id'         =>  $this->extend_id,
            'extend_package'    =>  $extendPackage->title ?? "",
            'max_user'          =>  $servicePackage->max_user,
            'count_user'        =>  $companyUsage->count_user ?? 0,
            'count_sub_user'    =>  $this->users->filter(function ($user) use ($commonService) {
                                        return $commonService->isSubUser(json_decode($user->userRole->permissions ?? '[]', true));
                                    })->count(),
            'max_data'          =>  $handleResourceService->convertByteToGigaByte($this->_getMaxData()),
            'count_data'        =>  $companyUsage->count_data ? $handleResourceService->convertByteToGigaByte($companyUsage->count_data) : 0,
            'created_at'        =>  $this->created_at,
            'ts_created_at'     =>  strtotime($this->created_at),
            'contract'          =>  new ListInfoContractResource($contract),
        ];
    }

    private function _getMaxData()
    {
        $servicePackage = $this->servicePackage;
        $extendPackage = $this->extendPackage;

        $maxData = $servicePackage->max_user * $servicePackage->max_user_data;
        if ($extendPackage) {
            $maxData += $extendPackage->extend_data;
        }
        return $maxData;
    }
}
