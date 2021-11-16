<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class UserUsageModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_usages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'count_album', 'count_data', 'package_data', 'extend_data'];

	/**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }
}
