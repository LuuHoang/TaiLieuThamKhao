<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlbumPDFFormatResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            =>  $this->id,
            'title'         =>  $this->title,
            'description'   =>  $this->description,
            'cover_page'    =>  $this->cover_page,
            'content_page_id' => $this->content_page_id,
            'content_page'  =>  $this->content_page,
            'last_page'  =>  $this->last_page,
            'number_images' =>  $this->number_images
        ];
    }
}
