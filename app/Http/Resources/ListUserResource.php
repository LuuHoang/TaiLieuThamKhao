<?php

namespace App\Http\Resources;

use App\Services\CommonService;
use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;

class ListUserResource extends JsonResource
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
        $numberAlbum = $userUsage->count_album ?? 0;
        $totalSize = $userUsage->count_data ?? 0;
        $maxSize = $userUsage->package_data + $userUsage->extend_data;
        if (!$commonService->isSubUser(json_decode($this->userRole->permissions ?? '[]', true))) {
            $subUsers = $this->subUsers;
            if ($subUsers->isNotEmpty()) {
                foreach ($subUsers as $subUser) {
                    $numberAlbum += ($subUser->userUsage->count_album ?? 0);
                    $totalSize += ($subUser->userUsage->count_data ?? 0);
                }
            }
        } else {
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
            "id"            =>      $this->id,
            "full_name"     =>      $this->full_name,
            "email"         =>      $this->email,
            "address"       =>      $this->address,
            "avatar_path"   =>      $this->avatar_path,
            "avatar_url"    =>      $this->avatar_url,
            "staff_code"    =>      $this->staff_code,
            "department"    =>      $this->department,
            "position"      =>      $this->position,
            "role"          =>      new ListRoleResource($this->userRole),
            "user_created_data" =>  new ShortUserDetailResource($this->userCreated),
            "company_data"  =>      [
                "company_id"    =>  $this->company->id,
                "company_name"  =>  $this->company->company_name,
                "company_code"  =>  $this->company->company_code
            ],
            "usage_data"    =>      [
                "count_album"   =>  $numberAlbum,
                "count_data"    =>  $handleResourceService->convertByteToGigaByte($totalSize),
                "max_data"      =>  $handleResourceService->convertByteToGigaByte($maxSize),
                "package_data"  =>  $handleResourceService->convertByteToGigaByte($this->userUsage->package_data),
                "extend_data"   =>  $handleResourceService->convertByteToGigaByte($this->userUsage->extend_data)
            ]
        ];
    }
}
