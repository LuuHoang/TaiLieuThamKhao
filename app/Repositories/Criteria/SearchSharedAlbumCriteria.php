<?php

namespace App\Repositories\Criteria;

use App\Models\AbstractModel;
use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class SearchSharedAlbumCriteria implements CriteriaInterface
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
        $query = $model;
        if (!empty($this->_paramQuery['search'])) {
            $query = $query-> where(function ($query) {
                $query->where('email', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('full_name', 'LIKE' , '%' . $this->_paramQuery['search'] . '%');
            });
        }
        if(!empty($this->_paramQuery['sort']['id'])) {
            $query = $query->orderBy('id', $this->_paramQuery['sort']['id']);
        } else if(!empty($this->_paramQuery['sort']['album'])) {
            $query = $query->orderBy('album_id', $this->_paramQuery['sort']['album']);
        } else if(!empty($this->_paramQuery['sort']['name'])) {
            $query = $query->orderBy('full_name', $this->_paramQuery['sort']['name']);
        } else if(!empty($this->_paramQuery['sort']['email'])) {
            $query = $query->orderBy('email', $this->_paramQuery['sort']['email']);
        } else if(!empty($this->_paramQuery['sort']['status'])) {
            $query = $query->orderBy('status', $this->_paramQuery['sort']['status']);
        } else if(!empty($this->_paramQuery['sort']['date'])) {
            $query = $query->orderBy('created_at', $this->_paramQuery['sort']['date']);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }
        return $query;
    }
}
