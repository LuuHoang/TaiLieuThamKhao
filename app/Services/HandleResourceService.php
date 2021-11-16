<?php

namespace App\Services;

use App\Constants\Boolean;
use App\Http\Resources\AlbumInformationBlankResource;
use App\Http\Resources\AlbumInformationResource;
use App\Http\Resources\AlbumLocationInformationBlankResource;
use App\Http\Resources\AlbumLocationInformationResource;
use App\Http\Resources\AlbumLocationMediaInformationBlankResource;
use App\Http\Resources\AlbumLocationMediaInformationResource;
use Illuminate\Database\Eloquent\Collection;

class HandleResourceService extends AbstractService
{
    public function handleAlbumInformationBlankResource(Collection $albumInformation, Collection $albumProperties)
    {
        $usedAlbumPropertyIds = $albumInformation->pluck('album_property_id')->toArray();
        $albumInformationResource = AlbumInformationResource::collection($albumInformation);
        foreach ($albumProperties as $albumProperty) {
            $albumPropertyId = $albumProperty['id'];
            if (!in_array($albumPropertyId, $usedAlbumPropertyIds)) {
                $albumInformationResource[] = new AlbumInformationBlankResource($albumProperty);
            }
        }
        return $albumInformationResource;
    }

    public function handleAlbumLocationInformationBlankResource(Collection $albumLocationInformation, Collection $locationProperties)
    {
        $usedLocationPropertyIds = $albumLocationInformation->pluck('location_property_id')->toArray();
        $albumLocationInformationResource = AlbumLocationInformationResource::collection($albumLocationInformation);
        foreach ($locationProperties as $locationProperty) {
            $locationPropertyId = $locationProperty['id'];
            if (!in_array($locationPropertyId, $usedLocationPropertyIds)) {
                $albumLocationInformationResource[] = new AlbumLocationInformationBlankResource($locationProperty);
            }
        }
        return $albumLocationInformationResource;
    }

    public function handleAlbumLocationMediaInformationBlankResource(Collection $albumLocationMediaInformation, Collection $mediaProperties)
    {
        $usedMediaPropertyIds = $albumLocationMediaInformation->pluck('media_property_id')->toArray();
        $albumLocationMediaInformationResource = AlbumLocationMediaInformationResource::collection($albumLocationMediaInformation);
        foreach ($mediaProperties as $mediaProperty) {
            $mediaPropertyId = $mediaProperty['id'];
            if (!in_array($mediaPropertyId, $usedMediaPropertyIds)) {
                $albumLocationMediaInformationResource[] = new AlbumLocationMediaInformationBlankResource($mediaProperty);
            }
        }
        return $albumLocationMediaInformationResource;
    }

    public function handleAlbumTypeCheckedResource(int $checkedAlbumTypeId, Collection $albumTypes)
    {
        $albumTypeResource = [];
        foreach($albumTypes as $albumType) {
            $albumTypeResource[] = [
                "id" => $albumType->id,
                "title" => $albumType->title,
                "checked" => ($checkedAlbumTypeId == $albumType->id) ? Boolean::TRUE : Boolean::FALSE
            ];
        }
        return $albumTypeResource;
    }

    public function convertByteToGigaByte(int $byte)
    {
        return round($byte / (1024 * 1024 * 1024), 5);
    }

    public function convertGigaByteToByte(int $size)
    {
        return $size * 1024 * 1024 * 1024;
    }
}
