<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlbumLocationInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"                    => $this->id,
            "location_property_id"  => $this->locationProperty->id,
            "title"                 => $this->locationProperty->title,
            "type"                  => $this->locationProperty->type,
            "display"               => $this->locationProperty->display,
            "highlight"             => $this->locationProperty->highlight,
            "value"                 => $this->value,
            "deleted"               => !!$this->locationProperty->deleted_at
        ];
    }
}
