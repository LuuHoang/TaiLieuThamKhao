<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AppVersionModel extends AbstractModel
{
    use SoftDeletes;

    protected $table = 'app_versions';

    protected $fillable = ['name', 'en_description', 'active', 'version_ios', "version_android", 'ja_description', 'vi_description'];

    protected $casts = [
        'active' => 'boolean'
    ];
}
