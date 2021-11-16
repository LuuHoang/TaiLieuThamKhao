<?php

namespace App\Models;

use App\Constants\App;
use App\Constants\Boolean;
use App\Constants\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlbumLocationModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['album_id', 'title', 'description'];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['album'];

    /**
     * @return HasMany
     */
    public function albumLocationMedias()
    {
        return $this->hasMany('App\Models\AlbumLocationMediaModel', 'album_location_id');
    }

    /**
     * @return HasOne
     */
    public function albumLocationImageLatest()
    {
        return $this->hasOne('App\Models\AlbumLocationMediaModel', 'album_location_id')
            ->where('type',Media::TYPE_IMAGE)->latest();
    }

    /**
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\AlbumLocationCommentModel','album_location_id')->latest();
    }

    /**
     * @return HasMany
     */
    public function locationInformation()
    {
        return $this->hasMany('App\Models\AlbumLocationInformationModel','album_location_id');
    }

    /**
     * @return BelongsTo
     */
    public function album()
    {
        return $this->belongsTo('App\Models\AlbumModel', 'album_id');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($albumLocationEntity) {
            $albumLocationEntity->locationInformation()->delete();
            $albumLocationEntity->comments()->delete();
            foreach ($albumLocationEntity->albumLocationMedias as $albumLocationMediaEntity) {
                $albumLocationMediaEntity->delete();
            }
        });
    }
}
