<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShortMediaPropertyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id"        => $this->id,
            "title"     => $this->title,
            "type"      => $this->type
        ];
    }
}
