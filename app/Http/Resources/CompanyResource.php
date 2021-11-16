<?php

namespace App\Http\Resources;

use App\Constants\Disk;
use App\Models\CompanyModel;
use App\Services\CommonService;
use App\Services\HandleResourceService;
use App\Services\WebService\ContractService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * Class CompanyResource
 * @package App\Http\Resources
 * @mixin CompanyModel
 */
class CompanyResource extends JsonResource
{
    public function toArray($request): array
    {
        $handleResourceService = app(HandleResourceService::class);
        $commonService = app(CommonService::class);
        $servicePackage = $this->servicePackage;
        $extendPackage = $this->extendPackage;
        $companyUsage = $this->companyUsage;
        $contractService = app(ContractService::class);
        $contract = $contractService->getContractInfoByCompanyId($this->id);
        return [
            'id'                =>  $this->id,
            'company_name'      =>  $this->company_name,
            'company_code'      =>  $this->company_code,
            'contract'          => [
                'contract_name'     => $contract->phone_company_hire,
                'contract_code'     =>  $contract->contract_code,
                'date_signed'       =>  $contract->date_signed,
                'payment_status'    =>  $contract->payment_status,
                'contract_status'   =>  $contract->contract_status,
                'effective_date'    =>  $contract->effective_date,
                'end_date'          =>  $contract->end_date,
                'employee_represent'=>  $contract->admin->full_name,
            ],
            'color'             =>  $this->color,
            'representative'    =>  $this->representative,
            'tax_code'          => $this->tax_code,
            'logo_path'         =>  $this->logo_path,
            'logo_url'          =>  Storage::disk(Disk::COMPANY)->url($this->logo_path),
            'address'           =>  $this->address,
            'service_id'        =>  $this->service_id,
            'service_package'   =>  $servicePackage->title,
            'service_package_price'  =>  $servicePackage->price,
            'extend_id'         =>  $this->extend_id,
            'extend_package'    =>  $extendPackage->title ?? "",
            'max_user'          =>  $servicePackage->max_user,
            'max_user_data'     =>  $handleResourceService->convertByteToGigaByte($servicePackage->max_user_data),
            'count_user'        =>  $companyUsage->count_user ?? 0,
            'max_data'          =>  $handleResourceService->convertByteToGigaByte($this->_getMaxData()),
            'count_data'        =>  $companyUsage->count_data ? $handleResourceService->convertByteToGigaByte($companyUsage->count_data) : 0,
            'count_sub_user'    =>  $this->users->filter(function ($user) use ($commonService) {
                                        return $commonService->isSubUser(json_decode($user->userRole->permissions ?? '[]', true));
                                    })->count(),
            'created_at'        =>  $this->created_at,
            'ts_created_at'     =>  strtotime($this->created_at),
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
