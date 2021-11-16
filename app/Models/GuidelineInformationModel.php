<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuidelineInformationModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'guideline_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['guideline_id', 'title', 'content', 'type'];

    /**
     * @return HasMany
     */
    public function guidelineInformationMedias()
    {
        return $this->hasMany('App\Models\GuidelineInformationMediaModel', 'guideline_information_id');
    }

    /**
     * @return BelongsTo
     */
    public function guideline()
    {
        return $this->belongsTo('App\Models\GuidelineModel', 'guideline_id');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($guidelineInformationEntity) {
            $guidelineInformationMediaEntities = $guidelineInformationEntity->guidelineInformationMedias()->get();
            foreach ($guidelineInformationMediaEntities as $guidelineInformationMediaEntity) {
                $guidelineInformationMediaEntity->delete();
            }
        });
    }
}
