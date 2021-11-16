<?php

namespace App\Http\Resources;

use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;

class ListExtendPackagesResource extends JsonResource
{
    public function toArray($request)
    {
        $handleResourceService = app(HandleResourceService::class);
        return [
            "id"            =>  $this->id,
            "title"         =>  $this->title,
            "description"   =>  $this->description,
            "extend_user"   =>  $this->extend_user,
            "extend_data"   =>  $handleResourceService->convertByteToGigaByte($this->extend_data),
            "price"         =>  $this->price,
            "count_company" =>  $this->count_company
        ];
    }
}
