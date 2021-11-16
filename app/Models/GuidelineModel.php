<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuidelineModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'guidelines';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'title', 'content'];

    /**
     * @return HasMany
     */
    public function guidelineInformation()
    {
        return $this->hasMany('App\Models\GuidelineInformationModel', 'guideline_id');
    }

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\CompanyModel', 'company_id');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($guidelineEntity) {
            $guidelineInformationEntities = $guidelineEntity->guidelineInformation()->get();
            foreach ($guidelineInformationEntities as $guidelineInformationEntity) {
                $guidelineInformationEntity->delete();
            }
        });
    }
}
