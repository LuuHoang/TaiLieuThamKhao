<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SampleContractModel extends AbstractModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sample_contracts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name_sample_contract', 'description','tags','created_by','updated_by','category','content'];

    /**
     * @return hasMany
     */
    public function sampleContractProperties()
    {
        return $this->hasMany('App\Models\SampleContractPropertyModel', 'sample_contract_id');
    }
    /**
     * @return hasMany
     */
    public function contracts()
    {
        return $this->hasMany('App\Models\ContractModel', 'sample_contract_id');
    }
}
