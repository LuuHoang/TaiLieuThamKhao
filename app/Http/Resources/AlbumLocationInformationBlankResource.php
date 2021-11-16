<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlbumLocationInformationBlankResource extends JsonResource
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
            "id"                    => "",
            "location_property_id"  => $this->id,
            "title"                 => $this->title,
            "type"                  => $this->type,
            "display"               => $this->display,
            "highlight"             => $this->highlight,
            "value"                 => "",
            "deleted"               => false
        ];
    }
}
