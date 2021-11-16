<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class DepartmentModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'departments';

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
        return $this->hasMany('App\Models\UserModel', 'department_id');
    }
}
