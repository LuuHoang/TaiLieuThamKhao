<?php

namespace App\Repositories\Criteria;

use App\Models\AbstractModel;
use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class SearchUsersCriteria implements CriteriaInterface
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
        $query = $model
            ->leftJoin('user_usages', function($join) {
                $join->on('users.id', '=', 'user_usages.user_id')
                    ->whereNull('user_usages.deleted_at');
            })
            ->leftJoin('user_roles', function($join) {
                $join->on('users.role_id', '=', 'user_roles.id')
                    ->whereNull('user_roles.deleted_at');
            })
            ->join('companies', function($join) {
                $join->on('users.company_id', '=', 'companies.id')
                    ->whereNull('companies.deleted_at');
            });

        if (!empty($this->_paramQuery['search'])) {
            $query = $query-> where(function ($query) {
                $query->where('users.staff_code', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('users.full_name', 'LIKE' , '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('users.email', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('companies.company_name', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('companies.company_code', 'LIKE', '%' . $this->_paramQuery['search'] . '%');
            });
        }

        if (!empty($this->_paramQuery['filter']['company_ids'])) {
            $companyIds = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['company_ids']))));
            $query = $query->whereIn('users.company_id', $companyIds);
        }

        if (!empty($this->_paramQuery['filter']['user_types'])) {
            $userTypes = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['user_types']))));
            $query = $query->whereIn('users.role', $userTypes);
        }

        if(!empty($this->_paramQuery['sort']['id'])) {
            $query = $query->orderBy('users.id', $this->_paramQuery['sort']['id']);
        } else if(!empty($this->_paramQuery['sort']['staff_no'])) {
            $query = $query->orderBy('users.staff_code', $this->_paramQuery['sort']['staff_no']);
        } else if(!empty($this->_paramQuery['sort']['full_name'])) {
            $query = $query->orderBy('users.full_name', $this->_paramQuery['sort']['full_name']);
        } else if(!empty($this->_paramQuery['sort']['email'])) {
            $query = $query->orderBy('users.email', $this->_paramQuery['sort']['email']);
        } else if(!empty($this->_paramQuery['sort']['company'])) {
            $query = $query->orderBy('companies.company_name', $this->_paramQuery['sort']['company']);
        } else if(!empty($this->_paramQuery['sort']['album'])) {
            $query = $query->orderBy('user_usages.count_album', $this->_paramQuery['sort']['album']);
        } else if(!empty($this->_paramQuery['sort']['data'])) {
            $query = $query->orderBy('user_usages.count_data', $this->_paramQuery['sort']['data']);
        } else {
            $query = $query->orderBy('users.id', 'DESC');
        }
        return $query
            ->select([
                'users.id',
                'users.staff_code',
                'users.company_id',
                'users.full_name',
                'users.email',
                'users.address',
                'users.avatar_path',
                'users.department',
                'users.position',
                'users.role_id',
                'user_roles.name',
                'user_usages.count_data',
                'user_usages.count_album'
            ])
            ->groupBy([
                'users.id',
                'users.staff_code',
                'users.company_id',
                'users.full_name',
                'users.email',
                'users.address',
                'users.avatar_path',
                'users.department',
                'users.position',
                'users.role_id',
                'user_roles.name',
                'user_usages.count_data',
                'user_usages.count_album'
            ]);
    }
}
