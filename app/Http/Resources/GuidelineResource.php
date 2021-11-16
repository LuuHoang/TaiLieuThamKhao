<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuidelineResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            =>  $this->id,
            'title'         =>  $this->title,
            'content'       =>  $this->content,
            'created_at'    =>  $this->created_at,
            'information'   =>  GuidelineInformationResource::collection($this->guidelineInformation)
        ];
    }
}
