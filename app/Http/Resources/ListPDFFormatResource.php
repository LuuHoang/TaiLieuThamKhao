<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ListPDFFormatResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            =>  $this->id,
            'title'         =>  $this->title,
            'description'   =>  $this->description,
        ];
    }
}
