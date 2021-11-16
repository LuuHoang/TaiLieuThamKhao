<?php

namespace App\Models;

use App\Constants\Boolean;
use App\Constants\Disk;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class AlbumModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'albums';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'album_type_id', 'image_path', 'size', 'config', 'show_comment'];

    protected $casts = [
        'show_comment' => "boolean"
    ];

	/**
     * @return HasMany
     */
    public function albumLocations()
    {
        return $this->hasMany('App\Models\AlbumLocationModel', 'album_id');
    }

    public function albumInformations()
    {
        return $this->hasMany('App\Models\AlbumInformationModel', 'album_id');
    }

    public function sharedAlbums()
    {
        return $this->hasMany('App\Models\SharedAlbumModel', 'album_id');
    }

    public function sharedAlbumActives()
    {
        return $this->hasMany('App\Models\SharedAlbumModel', 'album_id')->where('status', '=', Boolean::TRUE);
    }

    public function albumPDFs()
    {
        return $this->hasMany('App\Models\AlbumPDFModel', 'album_id');
    }

	/**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }

    public function albumType()
    {
        return $this->belongsTo('App\Models\AlbumTypeModel', 'album_type_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::disk(Disk::IMAGE)->url($this->image_path) : null;
    }

    /**
     * @return HasOne
     */
    public function highlightInformation()
    {
        return $this->hasOne('App\Models\AlbumInformationModel', 'album_id')
            ->whereHas('albumProperty', function (Builder $query) {
                $query->where('highlight', '=', Boolean::TRUE);
            }
        );
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($albumEntity) {
            $userUsage = $albumEntity->user->userUsage;
            $userUsage->update([
                'count_album' => $userUsage->count_album + 1
            ]);
        });
        static::deleting(function ($albumEntity) {
            $albumEntity->albumInformations()->delete();
            $albumEntity->sharedAlbums()->delete();
            $albumEntity->albumPDFs()->delete();
            foreach ($albumEntity->albumLocations as $albumLocation) {
                $albumLocation->delete();
            }
        });
        static::deleted(function ($albumEntity) {
            Storage::disk(Disk::IMAGE)->delete($albumEntity->image_path);
        });
    }
}
