<?php

namespace App\Http\Resources;

use App\Constants\Disk;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"                    =>      $this->id,
            "full_name"             =>      $this->full_name,
            "email"                 =>      $this->email,
            "department"            =>      $this->department ?? "",
            "position"              =>      $this->position ?? "",
            "avatar_path"           =>      $this->avatar_path,
            "avatar_url"            =>      Storage::disk(Disk::USER)->url($this->avatar_path),
            "permissions"           =>      $this->userRole->permissions,
            "company_data"          =>      new CompanyResource($this->company),
            "setting_data" => [
                "image_size"        =>      $this->userSetting->image_size ?? null,
                "language"          =>      $this->userSetting->language ?? null,
                "voice"             =>      $this->userSetting->voice ?? null,
            ]
        ];
    }
}
