<?php

namespace App\Models;

use App\Constants\CommentCreator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlbumLocationCommentModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_location_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['album_location_id', 'creator_id', 'creator_type', 'content'];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'creator_id');
    }

    /**
     * @return BelongsTo
     */
    public function shareUser()
    {
        return $this->belongsTo('App\Models\SharedAlbumModel', 'creator_id');
    }

    /**
     * @return BelongsTo
     */
    public function albumLocation()
    {
        return $this->belongsTo('App\Models\AlbumLocationModel','album_location_id');
    }
}
