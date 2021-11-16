<?php

namespace App\Http\Resources;

use App\Constants\Disk;
use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AlbumListResource extends JsonResource
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
            "user_id"               => $this->user_id,
            "user_created_data"     => new ShortUserDetailResource($this->user),
            "image_path"            => $this->image_path ?? "",
            "image_url"             => $this->image_path ? Storage::disk(Disk::IMAGE)->url($this->image_path) : "",
            "album_type"            => [
                "id"                    => $this->albumType->id,
                "title"                 => $this->albumType->title
            ],
            "album_informations"    => $handleResourceService->handleAlbumInformationBlankResource($this->albumInformations, $this->user->company->albumProperties),
            "created_at"            => $this->created_at,
            "updated_at"            => $this->updated_at,
            "show_comment"          => $this->show_comment
        ];
    }
}
