<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlbumPDFFormatModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_pdf_formats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'album_type_id', 'description', 'cover_page', 'content_page', 'cover_path', 'content_path', 'number_images', 'preview_cover_path', 'preview_content_path', 'last_page', 'last_path', 'preview_last_path', 'content_page_id'];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }
}
