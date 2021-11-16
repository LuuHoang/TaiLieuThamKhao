<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyStampConfigModel extends AbstractModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_stamp_configs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['stamp_type', 'mounting_position','icon_path','text','company_id',];

    /**
     * @return hasOne
     */
    public function companies()
    {
        return $this->hasOne('App\Models\CompanyModel', 'company_id');
    }
}
