<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id"            =>  $this->id,
            "title"         =>  $this->title,
            "type"          =>  $this->type,
            "meta_data"     =>  json_decode($this->data),
            "created_time"  =>  $this->created_time,
            "status"        =>  $this->status
        ];
    }
}
