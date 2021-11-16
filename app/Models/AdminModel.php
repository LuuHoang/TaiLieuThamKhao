<?php

namespace App\Models;

use App\Constants\Disk;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class AdminModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['full_name', 'email', 'password', 'avatar_path'];

    /**
     * @return HasMany
     */
    public function adminTokens()
    {
        return $this->hasMany('App\Models\AdminTokenModel', 'admin_id');
    }
    /**
     * @return HasMany
     */
    public function createContract()
    {
        return $this->hasMany('App\Models\ContractModel', 'employee_represent');
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar_path ? Storage::disk(Disk::USER)->url($this->avatar_path) : null;
    }
}
