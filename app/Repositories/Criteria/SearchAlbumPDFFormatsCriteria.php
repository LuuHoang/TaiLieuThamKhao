<?php

namespace App\Repositories\Criteria;

use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;

class SearchAlbumPDFFormatsCriteria implements CriteriaInterface
{
    private $_paramQuery;

    public function __construct(Array $paramQuery)
    {
        $this->_paramQuery = $paramQuery;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        if (!empty($this->_paramQuery['search'])) {
            $model = $model-> where(function ($query) {
                $query->where('title', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('description', 'LIKE' , '%' . $this->_paramQuery['search'] . '%');
            });
        }
        return $model;
    }
}
