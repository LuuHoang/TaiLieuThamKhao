<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SampleContractPropertyModel extends AbstractModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sample_contract_properties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'data_type','sample_contract_id'];

    /**
     * @return hasOne
     */
    public function sampleContract()
    {
        return $this->hasOne('App\Models\SampleContractModel','id');
    }
}
