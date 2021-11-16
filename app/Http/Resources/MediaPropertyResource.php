<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaPropertyResource extends JsonResource
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
            "display"   => $this->display,
            "highlight" => $this->highlight,
            "require" => $this->require,
            "description" => $this->description,
        ];
    }
}
