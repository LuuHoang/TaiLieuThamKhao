<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AdminTokenModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['admin_id', 'token'];

    /**
     * @return BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo('App\Models\AdminModel', 'admin_id');
    }
}
