<?php

namespace App\Http\Resources;

use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicePackageResource extends JsonResource
{
    public function toArray($request): array
    {
        $handleResourceService = app(HandleResourceService::class);
        return [
            'id'            =>  $this->id,
            'title'         =>  $this->title,
            'max_user'      =>  $this->max_user,
            'max_user_data' =>  $handleResourceService->convertByteToGigaByte($this->max_user_data),
            'max_data'      =>  $handleResourceService->convertByteToGigaByte($this->max_user * $this->max_user_data),
            'price'         =>  $this->price
        ];
    }
}
