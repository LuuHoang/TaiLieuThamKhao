<?php

namespace App\Http\Resources\WebResources;

use App\Constants\Disk;
use App\Http\Resources\ShortUserDetailResource;
use App\Services\HandleResourceService;
use Illuminate\Support\Facades\Storage;

class AlbumListResource extends \App\Http\Resources\AlbumListResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $handleResourceService = app(HandleResourceService::class);
        return [
            "id"                    => $this->id,
            "user_created_data"     => new ShortUserDetailResource($this->user),
            "image_path"            => $this->image_path ?? "",
            "image_url"             => $this->image_path ? Storage::disk(Disk::IMAGE)->url($this->image_path) : "",
            "album_type"            => [
                "id"                    => $this->albumType->id,
                "title"                 => $this->albumType->title
            ],
            "album_informations"    => $handleResourceService->handleAlbumInformationBlankResource($this->albumInformations, $this->user->company->albumProperties),
            "album_size"            => $this->size ? $handleResourceService->convertByteToGigaByte($this->size) : 0,
            "created_at"            => $this->created_at,
            "show_comment"          => $this->show_comment
        ];
    }
}
