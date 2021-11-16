<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlbumPDFModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pdf_albums';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['album_id', 'file_name', 'status', 'style_id'];

    /**
     * @return BelongsTo
     */
    public function album()
    {
        return $this->belongsTo('App\Models\AlbumModel', 'album_id');
    }
}
