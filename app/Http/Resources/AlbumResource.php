<?php

namespace App\Http\Resources;

use App\Services\CommonService;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $currentUser = app('currentUser');
        $commonService = app(CommonService::class);
        return [
            "id"                    => $this->id,
            "image_path"            => $this->image_path ?? "",
            "user_id"               => $this->user_id,
            "album_type"            => [
                "id"                    => $this->albumType->id,
                "title"                 => $this->albumType->title

            ],
            "album_locations"       => AlbumLocationResource::collection($this->albumLocations),
            "album_informations"    => AlbumInformationResource::collection($this->albumInformations),
            "updated_at"            => $this->updated_at,
            "show_comment"          => $this->show_comment,
            "allow_update"          => $commonService->allowUpdateAlbum($currentUser, $this->resource)
        ];
    }
}
