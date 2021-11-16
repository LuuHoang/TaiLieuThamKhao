<?php

namespace App\Http\Resources;

use App\Constants\Disk;
use App\Constants\WebCommunication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SharedAlbumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $album = $this->album;
        $link = env('WEB_URL') . WebCommunication::SHARE_ALBUM_PATH. "?token=" . $this->token;
        return [
            'id'            =>  $this->id,
            'full_name'     =>  $this->full_name,
            'email'         =>  $this->email,
            'album_data'    =>  [
                'id'            =>  $album->id,
                "image_path"    =>  $album->image_path ?? "",
                "image_url"     =>  $album->image_path ? Storage::disk(Disk::IMAGE)->url($album->image_path) : "",
            ],
            'link'          =>  $link,
            'status'        =>  $this->status,
            'created_at'    =>  $this->created_at,
            'ts_created_at' =>  strtotime($this->created_at)
        ];
    }
}
