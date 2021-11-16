<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuidelineInformationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            =>  $this->id,
            'title'         =>  $this->title,
            'type'          =>  $this->type,
            'content'       =>  $this->content,
            'files'         =>  GuidelineInformationMediaResource::collection($this->guidelineInformationMedias)
        ];
    }
}
