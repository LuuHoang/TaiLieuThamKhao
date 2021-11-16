<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlbumLocationMediaInformationBlankResource extends JsonResource
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
            "id"                => "",
            "media_property_id" => $this->id,
            "title"             => $this->title,
            "type"              => $this->type,
            "display"           => $this->display,
            "highlight"         => $this->highlight,
            "value"             => "",
            "deleted"           => false
        ];
    }
}
