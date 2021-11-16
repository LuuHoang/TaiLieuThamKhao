<?php

namespace App\Http\Resources;

use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCompanyUserResource extends JsonResource
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
        return [
            "id"                    =>  $this->id,
            "full_name"             =>  $this->full_name,
            "email"                 =>  $this->email,
            "address"               =>  $this->address,
            "avatar_path"           =>  $this->avatar_path,
            "staff_code"            =>  $this->staff_code,
            "department"            =>  $this->department,
            "position"              =>  $this->position,
            "role"                  =>  new ListRoleResource($this->userRole),
            "package_information"   =>   [
                "max_size"              =>  $handleResourceService->convertByteToGigaByte($this->userUsage->package_data + $this->userUsage->extend_data),
                "package_size"          =>  $handleResourceService->convertByteToGigaByte($this->userUsage->package_data),
                "extend_size"           =>  $handleResourceService->convertByteToGigaByte($this->userUsage->extend_data)
            ]
        ];
    }
}
