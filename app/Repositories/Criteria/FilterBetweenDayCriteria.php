<?php

namespace App\Repositories\Criteria;

use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;

class FilterBetweenDayCriteria implements CriteriaInterface
{
    private $_paramQuery;

    public function __construct(Array $paramQuery)
    {
        $this->_paramQuery = $paramQuery;
    }

    /**
     * @inheritDoc
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where(function ($query) {
            $query->whereDate('created_at', '>=', $this->_paramQuery['start_day']->toDateString())
                ->whereDate('created_at', '<=', $this->_paramQuery['end_day']->toDateString());
        });
    }
}
