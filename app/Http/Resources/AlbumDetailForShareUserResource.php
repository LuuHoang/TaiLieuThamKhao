<?php

namespace App\Http\Resources;

use App\Constants\Disk;
use App\Services\HandleResourceService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AlbumDetailForShareUserResource extends JsonResource
{
    public function toArray($request)
    {
        $handleResourceService = app(HandleResourceService::class);
        return [
            "id"                    => $this->id,
            "user_id"               => $this->user_id,
            "user_created_data"     => new ShortUserDetailResource($this->user),
            "image_path"            => $this->image_path ?? "",
            "image_url"             => $this->image_path ? Storage::disk(Disk::IMAGE)->url($this->image_path) : "",
            "album_types"           => $handleResourceService->handleAlbumTypeCheckedResource($this->album_type_id, $this->user->company->albumTypes),
            "album_locations"       => AlbumLocationForShareUserResource::collection($this->albumLocations),
            "album_informations"    => $handleResourceService->handleAlbumInformationBlankResource($this->albumInformations, $this->user->company->albumProperties),
            "updated_at"            => $this->updated_at,
            "show_comment"          => $this->show_comment
        ];
    }
}
