<?php

namespace App\Http\Resources\WebResources;

use App\Http\Resources\ListRoleResource;
use App\Services\CommonService;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\Disk;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\Storage;

class UserLoginForWebResource extends JsonResource
{
    public function toArray($request)
    {
        $commonService = app(CommonService::class);
        return [
            "id"                    =>      $this->id,
            "full_name"             =>      $this->full_name,
            "email"                 =>      $this->email,
            "department"            =>      $this->department ?? "",
            "position"              =>      $this->position ?? "",
            "avatar_path"           =>      $this->avatar_path,
            "avatar_url"            =>      Storage::disk(Disk::USER)->url($this->avatar_path),
            "role"                  =>      new ListRoleResource($this->userRole),
            "permissions"           =>      $commonService->retrieveWebPermissions(json_decode($this->userRole->permissions ?? "[]", true)),
            "company_data"          =>      new CompanyResource($this->company),
            "contract"     => [
                "status"            =>      $this->company->contracts[0]->contract_status,
                "contract_code"     =>      $this->company->contracts[0]->contract_code ?? null,
            ],
            "setting_data" => [
                "image_size"        =>      $this->userSetting->image_size ?? null,
                "language"          =>      $this->userSetting->language ?? null,
                "voice"             =>      $this->userSetting->voice ?? null,
            ]
        ];
    }
}
