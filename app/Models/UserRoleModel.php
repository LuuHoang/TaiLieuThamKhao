<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class UserRoleModel extends AbstractModel
{
    use SoftDeletes;

    protected $table = "user_roles";

    protected $casts = [
        'permissions' => 'array',
        'is_admin' => 'boolean',
        'is_default' => 'boolean'
    ];

    protected $fillable = ['company_id', 'name', 'description', 'permissions'];

    public function company()
    {
        return $this->belongsTo('App\Models\CompanyModel', 'company_id');
    }

    public function users()
    {
        return $this->hasMany('App\Models\UserModel', 'role_id');
    }
}
