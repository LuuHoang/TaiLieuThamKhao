<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SharedAlbumModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shared_albums';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['album_id', 'user_id', 'token', 'full_name', 'email', 'password', 'status', 'message'];

    /**
     * @return BelongsTo
     */
    public function album()
    {
        return $this->belongsTo('App\Models\AlbumModel', 'album_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }
}
