<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AbstractModel
 *
 * @package App\Models
 *
 * @mixin Builder|Model
 */
abstract class AbstractModel extends Model
{
    /**
     * Connection name.
     *
     * @var string
     */
    protected $connection = 'mysql';

}
