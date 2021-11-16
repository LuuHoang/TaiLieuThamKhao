<?php


namespace App\Repositories\Criteria;


use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\DB;

class SearchSampleContractCriteria implements CriteriaInterface
{
    private $_paramQuery;

    public function __construct(Array $paramQuery)
    {
        $this->_paramQuery = $paramQuery;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model
            ->leftJoin('contracts', function($join) {
                $join->on('sample_contracts.id', '=', 'contracts.sample_contract_id');
            })

            ->groupBy([
                'sample_contracts.id','sample_contracts.created_at','sample_contracts.name_sample_contract',
                'sample_contracts.description','sample_contracts.tags','sample_contracts.created_by','sample_contracts.category'
            ])->select('sample_contracts.id','sample_contracts.created_at','sample_contracts.name_sample_contract',
                'sample_contracts.description','sample_contracts.tags','sample_contracts.created_by','sample_contracts.category',DB::raw('count(contracts.sample_contract_id) as totalContracts'));

        if (!empty($this->_paramQuery['search'])) {
            $query = $query-> where(function ($query) {
                $query->where('sample_contracts.id', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('sample_contracts.name_sample_contract', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('sample_contracts.description', 'LIKE' , '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('sample_contracts.tags', 'LIKE' , '%' . $this->_paramQuery['search'] . '%');
            });
        }
        if (!empty($this->_paramQuery['filter']['created_at'])) {
            $created_at = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['created_at']))));
            $query = $query->whereRaw('DATE(sample_contracts.created_at) = ?',$created_at);
        }else if(!empty($this->_paramQuery['filter']['created_by'])){
            $created_by = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['created_by']))));
            $query = $query->whereIn('sample_contracts.created_by', $created_by);
        }else if(!empty($this->_paramQuery['filter']['category'])){
            $category = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['category']))));
            $query = $query->whereIn('sample_contracts.category', $category);
        }

        if(!empty($this->_paramQuery['sort']['id'])) {
            $query = $query->orderBy('sample_contracts.id', $this->_paramQuery['sort']['id']);
        } else if(!empty($this->_paramQuery['sort']['name'])) {
            $query = $query->orderBy('sample_contracts.name_sample_contract', $this->_paramQuery['sort']['name']);
        } else if(!empty($this->_paramQuery['sort']['created_by'])) {
            $query = $query->orderBy('sample_contracts.created_by', $this->_paramQuery['sort']['created_by']);
        } else if(!empty($this->_paramQuery['sort']['created_at'])) {
            $query = $query->orderBy('sample_contracts.created_at', $this->_paramQuery['sort']['created_at']);
        }else {
            $query = $query->orderBy('sample_contracts.id', 'DESC');
        }
        return $query;
    }
}
