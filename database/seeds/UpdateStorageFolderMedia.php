<?php

use App\Constants\Disk;
use App\Constants\Media;
use App\Models\AlbumLocationMediaModel;
use App\Repositories\Repository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateStorageFolderMedia extends Seeder
{
    private $_albumLocationMediaRepository;

    public function __construct(AlbumLocationMediaModel $albumLocationMediaModel)
    {
        $this->_albumLocationMediaRepository = new Repository($albumLocationMediaModel);
    }

    public function run()
    {
        $mediaEntities = $this->_albumLocationMediaRepository->all();
        foreach ($mediaEntities as $mediaEntity) {
            $dataUpdate = [];
            $albumLocation = $mediaEntity->albumLocation;
            if (!$albumLocation) {
                $mediaEntity->delete();
                continue;
            }
            $album = $albumLocation->album;
            $folder = $album->user_id . '/' . $album->id . '/' . $albumLocation->id;
            if ($mediaEntity->type == Media::TYPE_IMAGE) {
                if (Storage::disk(Disk::IMAGE)->exists($mediaEntity->path)) {
                    Storage::disk(Disk::ALBUM)->put($folder . '/' . Disk::IMAGE . '/' . $mediaEntity->path, Storage::disk(Disk::IMAGE)->get($mediaEntity->path));
                    Storage::disk(Disk::IMAGE)->delete($mediaEntity->path);
                }
                $dataUpdate['path'] = $folder . '/' . Disk::IMAGE . '/' . Str::afterLast($mediaEntity->path, '/');
                if (!empty($mediaEntity->image_after_path)) {
                    if (Storage::disk(Disk::IMAGE)->exists($mediaEntity->image_after_path)) {
                        Storage::disk(Disk::ALBUM)->put($folder . '/' . Disk::IMAGE . '/' . $mediaEntity->image_after_path, Storage::disk(Disk::IMAGE)->get($mediaEntity->image_after_path));
                        Storage::disk(Disk::IMAGE)->delete($mediaEntity->image_after_path);
                    }
                    $dataUpdate['image_after_path'] = $folder . '/' . Disk::IMAGE . '/' . Str::afterLast($mediaEntity->image_after_path, '/');
                }
            } elseif ($mediaEntity->type == Media::TYPE_VIDEO) {
                if (Storage::disk(Disk::VIDEO)->exists($mediaEntity->path)) {
                    Storage::disk(Disk::ALBUM)->put($folder . '/' . Disk::VIDEO . '/' . $mediaEntity->path, Storage::disk(Disk::VIDEO)->get($mediaEntity->path));
                    Storage::disk(Disk::VIDEO)->delete($mediaEntity->path);
                }
                if (Storage::disk(Disk::THUMBNAIL)->exists($mediaEntity->thumbnail_path)) {
                    Storage::disk(Disk::ALBUM)->put($folder . '/' . Disk::THUMBNAIL . '/' . $mediaEntity->thumbnail_path, Storage::disk(Disk::THUMBNAIL)->get($mediaEntity->thumbnail_path));
                    Storage::disk(Disk::THUMBNAIL)->delete($mediaEntity->thumbnail_path);
                }
                $dataUpdate = [
                    'path' => $folder . '/' . Disk::VIDEO . '/' . Str::afterLast($mediaEntity->path, '/'),
                    'thumbnail_path' => $folder . '/' . Disk::THUMBNAIL . '/' . Str::afterLast($mediaEntity->thumbnail_path, '/')
                ];
            }
            $mediaEntity->update($dataUpdate);
        }
    }
}
