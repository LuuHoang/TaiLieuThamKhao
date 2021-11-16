<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePackageModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'max_user', 'max_user_data', 'price', 'description'];

	/**
     * @return HasMany
     */
    public function companies()
    {
        return $this->hasMany('App\Models\CompanyModel', 'service_id');
    }

    public function getCountCompanyAttribute()
    {
        return $this->companies()->count();
    }
    /**
     * @return HasMany
     */
    public function contracts()
    {
        return $this->hasMany('App\Models\ContractModel', 'service_package_id');
    }
}
