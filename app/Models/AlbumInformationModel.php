<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlbumInformationModel extends AbstractModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_informations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['album_id', 'album_property_id', 'value', 'value_list'];

    protected $casts = [
        'value_list' => 'array',
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['album'];

    /**
     * @return BelongsTo
     */
    public function albumProperty()
    {
        return $this->belongsTo('App\Models\AlbumPropertyModel', 'album_property_id')->withTrashed();
    }

    public function album()
    {
        return $this->belongsTo('App\Models\AlbumModel', 'album_id');
    }
}
