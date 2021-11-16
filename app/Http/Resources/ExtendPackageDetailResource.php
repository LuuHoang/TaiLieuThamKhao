<?php

namespace App\Http\Resources;

use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;

class ExtendPackageDetailResource extends JsonResource
{
    public function toArray($request)
    {
        $handleResourceService = app(HandleResourceService::class);
        return [
            "id"            =>  $this->id,
            "title"         =>  $this->title,
            "description"   =>  $this->description,
            "extend_user"   =>  $this->extend_user,
            "price"         =>  $this->price,
            "extend_data"   =>  $handleResourceService->convertByteToGigaByte($this->extend_data),
            "type"          =>  "Extend"
        ];
    }
}
