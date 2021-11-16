<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlbumTypeResource extends JsonResource
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
            "id"    => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "default"     => $this->default,
            "album_information" => AlbumPropertyResource::collection($this->albumProperties),
            "location_information" => LocationPropertyResource::collection($this->locationProperties),
            "media_information"    => MediaPropertyResource::collection($this->mediaProperties),
        ];
    }
}
