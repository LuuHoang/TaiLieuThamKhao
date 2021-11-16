<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListAlbumPDFFormatResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            =>  $this->id,
            'title'         =>  $this->title,
            'description'   =>  $this->description,
            'album_type_id' =>  $this->album_type_id,
            'user_creator'  =>  new ShortUserDetailResource($this->user)
        ];
    }
}
