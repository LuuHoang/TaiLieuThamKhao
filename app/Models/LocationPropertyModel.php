<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationPropertyModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'location_properties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'title', 'type', 'display', 'highlight','description','require','album_type_id'];

    /**
     * @return HasMany
     */
    public function albumLocationInformation()
    {
        return $this->hasMany('App\Models\AlbumLocationInformationModel', 'location_property_id');
    }

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\CompanyModel', 'company_id');
    }
}
