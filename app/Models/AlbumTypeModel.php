<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AlbumTypeModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'title','description','default'];

	/**
     * @return HasMany
     */
    public function albums()
    {
        return $this->hasMany('App\Models\AlbumModel', 'album_type_id');
    }

	/**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\CompanyModel', 'company_id');
    }
    /**
     * @return HasMany
     */
    public function albumProperties()
    {
        return $this->hasMany('App\Models\AlbumPropertyModel', 'album_type_id');
    }
    /**
     * @return HasMany
     */
    public function locationProperties()
    {
        return $this->hasMany('App\Models\LocationPropertyModel', 'album_type_id');
    }
    /**
     * @return HasMany
     */
    public function mediaProperties()
    {
        return $this->hasMany('App\Models\MediaPropertyModel', 'album_type_id');
    }
}
