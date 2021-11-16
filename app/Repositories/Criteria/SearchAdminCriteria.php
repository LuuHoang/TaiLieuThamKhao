<?php


namespace App\Repositories\Criteria;


use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;

class SearchAdminCriteria implements CriteriaInterface
{
    private $_paramQuery;

    public function __construct(Array $paramQuery)
    {
        $this->_paramQuery = $paramQuery;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model;
        if (!empty($this->_paramQuery['search'])) {
            $query = $query-> where(function ($query) {
                $query->where('admins.full_name', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('admins.email', 'LIKE', '%' . $this->_paramQuery['search'] . '%');
            });
        }
        if(!empty($this->_paramQuery['sort']['id'])) {
            $query = $query->orderBy('admins.id', $this->_paramQuery['sort']['id']);
        } else if(!empty($this->_paramQuery['sort']['full_name'])) {
            $query = $query->orderBy('admins.full_name', $this->_paramQuery['sort']['full_name']);
        } else if(!empty($this->_paramQuery['sort']['email'])) {
            $query = $query->orderBy('admins.email', $this->_paramQuery['sort']['email']);
        }else {
            $query = $query->orderBy('admins.id', 'DESC');
        }
        return $query
            ->select([
                'admins.id',
                'admins.full_name',
                'admins.email',
                'admins.avatar_path',
                'admins.created_at',
                'admins.updated_at'
            ])
            ->groupBy([
                'admins.id',
                'admins.full_name',
                'admins.email',
                'admins.avatar_path',
                'admins.created_at',
                'admins.updated_at'
            ]);
    }
}
