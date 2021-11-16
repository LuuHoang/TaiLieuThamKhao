<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlbumPropertyModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_properties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'title', 'description', 'type', 'require', 'display', 'highlight','album_type_id'];

	/**
     * @return HasMany
     */
    public function albumInformations()
    {
        return $this->hasMany('App\Models\AlbumInformationModel', 'album_property_id');
    }

	/**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\CompanyModel', 'company_id');
    }
}
