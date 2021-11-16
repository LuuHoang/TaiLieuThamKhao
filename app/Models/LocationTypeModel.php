<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class LocationTypeModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'location_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'title'];

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\CompanyModel', 'company_id');
    }
}
