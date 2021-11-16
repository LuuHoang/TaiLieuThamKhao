<?php

namespace App\Http\Resources;

use App\Constants\Boolean;
use App\Constants\Disk;
use App\Services\CommonService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VerifyUserForAppResources extends JsonResource
{
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
            'company_data'  =>  new CompanyResource($this->company),
            'setting_data'  =>  new UserSettingResource($this->userSetting)
        ];
    }
}
