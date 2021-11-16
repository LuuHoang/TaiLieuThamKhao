<?php

namespace App\Http\Resources;

use App\Constants\InputType;
use App\Utils\Util;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumInformationResource extends JsonResource
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
            "id"                => $this->id,
            "album_property_id" => $this->albumProperty->id,
            "title"             => $this->albumProperty->title,
            "description"       => $this->albumProperty->description,
            "type"              => $this->albumProperty->type,
            "require"           => $this->albumProperty->require,
            "display"           => $this->albumProperty->display,
            "highlight"         => $this->albumProperty->highlight,
            "value"             => $this->value,
            "value_list"        => $this->albumProperty->type === InputType::PDFS ? PdfFileResource::collection(Util::getPdfFilesOfUser($this->value_list)) : [],
            "deleted"           => !!$this->albumProperty->deleted_at,
        ];
    }
}
