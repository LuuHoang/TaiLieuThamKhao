<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlbumLocationMediaInformationResource extends JsonResource
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
            "media_property_id"     => $this->mediaProperty->id,
            "title"                 => $this->mediaProperty->title,
            "type"                  => $this->mediaProperty->type,
            "display"               => $this->mediaProperty->display,
            "highlight"             => $this->mediaProperty->highlight,
            "value"                 => $this->value,
            "deleted"               => !!$this->mediaProperty->deleted_at
        ];
    }
}
