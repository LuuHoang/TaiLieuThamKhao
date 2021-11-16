<?php

namespace App\Repositories\Criteria;

use App\Models\AbstractModel;
use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class SearchCompanyCriteria implements CriteriaInterface
{
    private $_paramQuery;

    public function __construct(Array $paramQuery)
    {
        $this->_paramQuery = $paramQuery;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model
            ->leftJoin('service_packages', function($join) {
                $join->on('companies.service_id', '=', 'service_packages.id')
                    ->whereNull('service_packages.deleted_at');
            })
            ->leftJoin('company_usages', function ($join) {
                $join->on('companies.id', '=', 'company_usages.company_id')
                    ->whereNull('company_usages.deleted_at');
            })
            ->leftJoin('contracts', function ($join) {
            $join->on('companies.id', '=', 'contracts.company_id')
                ->whereNull('contracts.deleted_at');
            })
            ->leftJoin('extend_packages', function ($join) {
                $join->on('extend_packages.id', '=', 'contracts.extend_package_id')
                    ->whereNull('extend_packages.deleted_at');
            });
        if (!empty($this->_paramQuery['search'])) {
            $query = $query-> where(function ($query) {
                $query->where('companies.company_name', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('companies.company_code', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('contracts.contract_code', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('companies.address', 'LIKE' , '%' . $this->_paramQuery['search'] . '%');
            });
        }
        if (!empty($this->_paramQuery['filter']['package_ids'])) {
            $userTypes = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['package_ids']))));
            $query = $query->whereIn('companies.service_id', $userTypes);
        }
        if (!empty($this->_paramQuery['filter']['contract_status'])) {
            $userTypes = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['contract_status']))));
            $query = $query->whereIn('contracts.contract_status', $userTypes);
        }
        if (!empty($this->_paramQuery['filter']['sample_contract_id'])) {
            $userTypes = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['sample_contract_id']))));
            $query = $query->whereIn('contracts.sample_contract_id', $userTypes);
        }
        if (!empty($this->_paramQuery['filter']['created_at'])) {
            $created_at = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['created_at']))));
            $query = $query->whereRaw('DATE(contracts.created_at) = ?',$created_at);
        }
        if (!empty($this->_paramQuery['filter']['end_date'])) {
            $end_date = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['end_date']))));
            $query = $query->whereRaw('DATE(contracts.end_date) = ?',$end_date);
        }


        if(!empty($this->_paramQuery['sort']['id'])) {
            $query = $query->orderBy('companies.id', $this->_paramQuery['sort']['id']);
        } else if(!empty($this->_paramQuery['sort']['name'])) {
            $query = $query->orderBy('companies.company_name', $this->_paramQuery['sort']['name']);
        } else if(!empty($this->_paramQuery['sort']['code'])) {
            $query = $query->orderBy('companies.company_code', $this->_paramQuery['sort']['code']);
        } else if(!empty($this->_paramQuery['sort']['address'])) {
            $query = $query->orderBy('companies.address', $this->_paramQuery['sort']['address']);
        } else if(!empty($this->_paramQuery['sort']['package'])) {
            $query = $query->orderBy('service_packages.title', $this->_paramQuery['sort']['package']);
        }else if(!empty($this->_paramQuery['sort']['extend_package'])) {
            $query = $query->orderBy('extend_packages.title', $this->_paramQuery['sort']['extend_package']);
        }else if(!empty($this->_paramQuery['sort']['user'])) {
            $query = $query->orderBy('company_usages.count_user', $this->_paramQuery['sort']['user']);
        } else {
            $query = $query->orderBy('companies.id', 'DESC');
        }
        return $query
            ->select([
                'companies.id',
                'companies.service_id',
                'companies.color',
                'companies.logo_path',
                'companies.company_name',
                'companies.company_code',
                'companies.address',
                'companies.extend_id',
                'companies.created_at',
            ])
            ->groupBy([
                'companies.id',
                'companies.service_id',
                'companies.color',
                'companies.logo_path',
                'companies.company_name',
                'companies.company_code',
                'companies.address',
                'companies.extend_id',
                'companies.created_at',
                'company_usages.count_user',
            ]);
    }
}
