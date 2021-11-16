<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationPropertyResource extends JsonResource
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
            "id"        => $this->id,
            "title"     => $this->title,
            "type"      => $this->type,
            "description"      => $this->description,
            "require"   => $this->require,
            "display"   => $this->display,
            "highlight" => $this->highlight,
            "album_type_id" => $this->album_type_id
        ];
    }
}
