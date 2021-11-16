<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinkVersionModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'link_version';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ios', 'android'];
}
