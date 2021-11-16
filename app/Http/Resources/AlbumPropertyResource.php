<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlbumPropertyResource extends JsonResource
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
            "description" => $this->description,
            "type"      => $this->type,
            "require"   => $this->require,
            "display"   => $this->display,
            "highlight" => $this->highlight,
            "album_type_id" => $this->album_type_id,
        ];
    }
}
