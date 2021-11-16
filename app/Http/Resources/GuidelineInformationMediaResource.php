<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuidelineInformationMediaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                =>  $this->id,
            'type'              =>  $this->type,
            'path'              =>  $this->path,
            'url'               =>  $this->url,
            'thumbnail_path'    =>  $this->thumbnail_path,
            'thumbnail_url'     =>  $this->thumbnail_url
        ];
    }
}
