<?php


namespace App\Http\Resources;


use App\Constants\Disk;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AdminCreatedResource extends JsonResource
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
            "id"                    =>      $this->id,
            "full_name"             =>      $this->full_name,
            "email"                 =>      $this->email,
            "avatar_path"           =>      $this->avatar_path,
            "avatar_url"            =>      Storage::disk(Disk::ADMIN)->url($this->avatar_path),
        ];
    }
}
