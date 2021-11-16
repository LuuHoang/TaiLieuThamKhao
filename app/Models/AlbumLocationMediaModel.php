<?php

namespace App\Models;

use App\Constants\App;
use App\Constants\Disk;
use App\Constants\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class AlbumLocationMediaModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_location_medias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['album_location_id', 'path', 'thumbnail_path', 'description', 'size', 'type', 'created_time', 'image_after_path', 'after_size', 'before_size'];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['albumLocation'];

	/**
     * @return BelongsTo
     */
    public function albumLocation()
    {
        return $this->belongsTo('App\Models\AlbumLocationModel', 'album_location_id');
    }

    /**
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\AlbumLocationMediaCommentModel','album_location_media_id')->latest();
    }

    /**
     * @return HasMany
     */
    public function mediaInformation()
    {
        return $this->hasMany('App\Models\AlbumLocationMediaInformationModel','album_location_media_id');
    }

    public function getUrlAttribute()
    {
        if ($this->type == Media::TYPE_IMAGE) {
            return $this->path ? Storage::disk(Disk::ALBUM)->url($this->path) : null;
        } elseif ($this->type == Media::TYPE_VIDEO) {
            return $this->path ? Storage::disk(Disk::ALBUM)->url($this->path) : null;
        } else {
            return null;
        }
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->type == Media::TYPE_VIDEO) {
            return $this->thumbnail_path ? Storage::disk(Disk::ALBUM)->url($this->thumbnail_path) : null;
        } else {
            return null;
        }
    }

    public function getImageAfterUrlAttribute()
    {
        if ($this->type == Media::TYPE_IMAGE) {
            return $this->image_after_path ? Storage::disk(Disk::ALBUM)->url($this->image_after_path) : null;
        } else {
            return null;
        }
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($albumLocationMediaEntity) {
            $albumLocationMediaEntity->mediaInformation()->delete();
            $albumLocationMediaEntity->comments()->delete();
        });
        static::deleted(function ($albumLocationMediaEntity) {
            if ($albumLocationMediaEntity->type == Media::TYPE_IMAGE) {
                Storage::disk(Disk::ALBUM)->delete($albumLocationMediaEntity->path);
                if (!empty($albumLocationMediaEntity->image_after_path)) {
                    Storage::disk(Disk::ALBUM)->delete($albumLocationMediaEntity->image_after_path);
                }
            } elseif ($albumLocationMediaEntity->type == Media::TYPE_VIDEO) {
                Storage::disk(Disk::ALBUM)->delete($albumLocationMediaEntity->path);
                Storage::disk(Disk::ALBUM)->delete($albumLocationMediaEntity->thumbnail_path);
            }
        });
    }
}
