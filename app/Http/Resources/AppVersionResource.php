<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppVersionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "en_description" => $this->en_description,
            "ja_description" => $this->ja_description,
            "vi_description" => $this->vi_description,
            "active" => $this->active,
            "version_ios" => $this->version_ios,
            "version_android" => $this->version_android,
            "created_at" => $this->created_at
        ];
    }
}
