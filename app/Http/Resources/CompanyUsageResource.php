<?php

namespace App\Http\Resources;

use App\Services\CommonService;
use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyUsageResource extends JsonResource
{
    public function toArray($request)
    {
        $handleResourceService = app(HandleResourceService::class);
        $commonService = app(CommonService::class);
        return [
            'count_user'            =>  $this->count_user,
            'count_sub_user'        =>  $this->company->users->filter(function ($user) use ($commonService) {
                                            return $commonService->isSubUser(json_decode($user->userRole->permissions ?? '[]', true));
                                        })->count(),
            'count_data'            =>  $handleResourceService->convertByteToGigaByte($this->count_data),
            'count_extend_data'     =>  $handleResourceService->convertByteToGigaByte($this->count_extend_data)
        ];
    }
}
