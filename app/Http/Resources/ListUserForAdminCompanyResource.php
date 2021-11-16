<?php

namespace App\Http\Resources;

use App\Services\CommonService;
use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;

class ListUserForAdminCompanyResource extends JsonResource
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
            'id'                =>  $this->id,
            'staff_code'        =>  $this->staff_code,
            'full_name'         =>  $this->full_name,
            'email'             =>  $this->email,
            'address'           =>  $this->address,
            'avatar_path'       =>  $this->avatar_path,
            "avatar_url"        =>  $this->avatar_url,
            'role'              =>  new ListRoleResource($this->userRole),
            'department'        =>  $this->department,
            'position'          =>  $this->position,
            'number_albums'     =>  $numberAlbum,
            'total_size'        =>  $handleResourceService->convertByteToGigaByte($totalSize),
            'max_size'          =>  $handleResourceService->convertByteToGigaByte($maxSize),
            "user_created_data" =>  new ShortUserDetailResource($this->userCreated)
        ];
    }
}
