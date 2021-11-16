<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ExtendPackageModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'extend_packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'extend_user', 'extend_data', 'description', 'price'];

    /**
     * @return HasMany
     */
    public function companies()
    {
        return $this->hasMany('App\Models\CompanyModel', 'extend_id');
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
        return $this->hasMany('App\Models\ContractModel', 'extend_package_id');
    }
}
