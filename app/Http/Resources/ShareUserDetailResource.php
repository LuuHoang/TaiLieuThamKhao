<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShareUserDetailResource extends JsonResource
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
            "shared_album_id"       =>      $this->id,
            "full_name"             =>      $this->full_name,
            "email"                 =>      $this->email
        ];
    }
}
