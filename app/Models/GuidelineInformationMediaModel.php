<?php

namespace App\Models;

use App\Constants\Disk;
use App\Constants\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class GuidelineInformationMediaModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'guideline_information_medias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['guideline_information_id', 'path', 'thumbnail_path', 'type'];

    /**
     * @return BelongsTo
     */
    public function guidelineInformation()
    {
        return $this->belongsTo('App\Models\GuidelineInformationModel', 'guideline_information_id');
    }

    public function getUrlAttribute()
    {
        if ($this->type == Media::TYPE_IMAGE) {
            return $this->path ? Storage::disk(Disk::IMAGE)->url($this->path) : null;
        } elseif ($this->type == Media::TYPE_VIDEO) {
            return $this->path ? Storage::disk(Disk::VIDEO)->url($this->path) : null;
        } elseif ($this->type == Media::TYPE_PDF) {
            return $this->path ? Storage::disk(Disk::PDF)->url($this->path) : null;
        } else {
            return null;
        }
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->type == Media::TYPE_VIDEO) {
            return $this->thumbnail_path ? Storage::disk(Disk::THUMBNAIL)->url($this->thumbnail_path) : null;
        } else {
            return null;
        }
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($guidelineInformationMediaEntity) {
            if ($guidelineInformationMediaEntity->type == Media::TYPE_IMAGE) {
                Storage::disk(Disk::IMAGE)->delete($guidelineInformationMediaEntity->path);
            } elseif ($guidelineInformationMediaEntity->type == Media::TYPE_VIDEO) {
                Storage::disk(Disk::VIDEO)->delete($guidelineInformationMediaEntity->path);
                Storage::disk(Disk::THUMBNAIL)->delete($guidelineInformationMediaEntity->thumbnail_path);
            } elseif ($guidelineInformationMediaEntity->type == Media::TYPE_PDF) {
                Storage::disk(Disk::PDF)->delete($guidelineInformationMediaEntity->path);
            }
        });
    }
}
