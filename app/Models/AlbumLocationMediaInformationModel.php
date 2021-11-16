<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlbumLocationMediaInformationModel extends AbstractModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_location_media_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['album_location_media_id', 'media_property_id', 'value'];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['albumLocationMedia'];

    /**
     * @return BelongsTo
     */
    public function mediaProperty()
    {
        return $this->belongsTo('App\Models\MediaPropertyModel', 'media_property_id')->withTrashed();
    }

    public function albumLocationMedia()
    {
        return $this->belongsTo('App\Models\AlbumLocationMediaModel', 'album_location_media_id');
    }
}
