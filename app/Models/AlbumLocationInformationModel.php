<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlbumLocationInformationModel extends AbstractModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_location_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['album_location_id', 'location_property_id', 'value'];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['albumLocation'];

    /**
     * @return BelongsTo
     */
    public function locationProperty()
    {
        return $this->belongsTo('App\Models\LocationPropertyModel', 'location_property_id')->withTrashed();
    }

    public function albumLocation()
    {
        return $this->belongsTo('App\Models\AlbumLocationModel', 'album_location_id');
    }
}
