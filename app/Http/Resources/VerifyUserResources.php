<?php

namespace App\Http\Resources;

use App\Constants\Boolean;
use App\Constants\Disk;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VerifyUserResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $notifications = $this->notifications;
        return [
            'id'            =>  $this->id,
            'avatar_path'   =>  $this->avatar_path,
            "avatar_url"    =>  Storage::disk(Disk::USER)->url($this->avatar_path),
            'full_name'     =>  $this->full_name,
            'email'         =>  $this->email,
            'notification' =>  [
                "unread"    => $notifications->where('status', '=', Boolean::FALSE)->count(),
                "total"     => $notifications->count()
            ],
            "permissions"   => $this->userRole->permissions,
            'company_data'  =>  new CompanyResource($this->company),
            'setting_data'  =>  new UserSettingResource($this->userSetting)
        ];
    }
}
