<?php

namespace App\Http\Resources;

use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;

class ListServicePackagesResource extends JsonResource
{
    public function toArray($request)
    {
        $handleResourceService = app(HandleResourceService::class);
        return [
            "id"            =>  $this->id,
            "title"         =>  $this->title,
            "description"   =>  $this->description,
            "max_user"      =>  $this->max_user,
            "price"         =>  $this->price,
            "max_user_data" =>  $handleResourceService->convertByteToGigaByte($this->max_user_data),
            "max_data"      =>  $handleResourceService->convertByteToGigaByte($this->max_user_data * $this->max_user),
            "count_company" =>  $this->count_company
        ];
    }
}
