<?php

namespace App\Http\Resources;

use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;

class ExtendPackageResource extends JsonResource
{
    public function toArray($request): array
    {
        $handleResourceService = app(HandleResourceService::class);
        return [
            'id'            =>  $this->id,
            'title'         =>  $this->title,
            'extend_data'   =>  $handleResourceService->convertByteToGigaByte($this->extend_data),
            'price'         =>  $this->price
        ];
    }
}
