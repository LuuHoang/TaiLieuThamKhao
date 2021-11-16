<?php

namespace App\Http\Resources;

use App\Services\CommonService;
use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResourceV2 extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $handleResourceService = app(HandleResourceService::class);
        $commonService = app(CommonService::class);
        $userUsage = $this->userUsage;
        $maxSize = $userUsage->package_data + $userUsage->extend_data;
        if ($commonService->isSubUser(json_decode($this->userRole->permissions ?? '[]', true))) {
            $userCreated = $this->userCreated;
            if ($userCreated != null) {
                $subUsers = $userCreated->subUsers;
                $maxSize = $userCreated->userUsage->package_data + $userCreated->userUsage->extend_data - $userCreated->userUsage->count_data;
                if ($subUsers->isNotEmpty()) {
                    foreach ($subUsers as $subUser) {
                        if ($subUser->id != $this->id) {
                            $maxSize = $maxSize - $subUser->userUsage->count_data;
                        }
                    }
                }
            }
        }
        return [
            "id"                    =>      $this->id,
            "full_name"             =>      $this->full_name,
            "email"                 =>      $this->email,
            "address"               =>      $this->address,
            "avatar_path"           =>      $this->avatar_path,
            "avatar_url"            =>      $this->avatar_url,
            "staff_code"            =>      $this->staff_code,
            "department"            =>      $this->department,
            "position"              =>      $this->position,
            "role"                  =>      new ListRoleResource($this->userRole),
            "company_id"            =>      $this->company_id,
            "company_name"          =>      $this->company->company_name,
            "company_code"          =>      $this->company->company_code,
            "user_created_data"     =>      new ShortUserDetailResource($this->userCreated),
            "package_information"   =>      [
                "max_size"          =>      $handleResourceService->convertByteToGigaByte($maxSize),
                "package_size"      =>      $handleResourceService->convertByteToGigaByte($userUsage->package_data),
                "extend_size"       =>      $handleResourceService->convertByteToGigaByte($userUsage->extend_data)
            ]
        ];
    }
}
