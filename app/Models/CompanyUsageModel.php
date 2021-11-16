<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyUsageModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_usages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'count_user', 'count_extend_data', 'count_data'];

	/**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\CompanyModel', 'company_id');
    }
}
