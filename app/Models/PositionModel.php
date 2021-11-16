<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PositionModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'positions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title'];

    /**
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Models\UserModel', 'position_id');
    }
}
