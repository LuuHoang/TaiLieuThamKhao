<?php

namespace App\Repositories\Contracts;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Interface CriteriaInterface
 * @package App\Repositories\Contracts
 */
interface CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param Builder|AbstractModel $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository);
}
