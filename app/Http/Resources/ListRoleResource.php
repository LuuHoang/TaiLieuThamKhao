<?php

namespace App\Http\Resources;

use App\Constants\Permission;
use App\Constants\Platform;
use App\Services\CommonService;
use Illuminate\Http\Resources\Json\JsonResource;

class ListRoleResource extends JsonResource
{
    public function toArray($request)
    {
        $commonService = app(CommonService::class);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "is_admin" => $this->is_admin,
            "is_default" => $this->is_default,
            "is_sub_user" => $commonService->checkPermission(json_decode($this->permissions ?? "[]", true), Permission::SUB_USER, Platform::WEB),
        ];
    }
}
