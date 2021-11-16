<?php


namespace App\Repositories\Criteria;


use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;

class SearchContractByCompanyCriteria implements CriteriaInterface
{
    private $_paramQuery;

    public function __construct(array $paramQuery)
    {
        $this->_paramQuery = $paramQuery;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model
            ->where('contracts.company_id','=',$this->_paramQuery['company']);
        if (!empty($this->_paramQuery['search'])) {
            $query = $query->where(function ($query) {
                $query->where('contracts.represent_company_hire', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('contracts.phone_company_hire', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('contracts.name_company_rental', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('contracts.address_company_rental', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('contracts.represent_company_rental', 'LIKE', '%' . $this->_paramQuery['search'] . '%');
            });
        }
        if (!empty($this->_paramQuery['filter']['package_ids'])) {
            $userTypes = array_unique(array_values(array_filter(explode(",", $this->_paramQuery['filter']['package_ids']))));
            $query = $query->whereIn('contracts.service_package_id', $userTypes);
        }
        if (!empty($this->_paramQuery['filter']['contract_status'])) {
            $userTypes = array_unique(array_values(array_filter(explode(",", $this->_paramQuery['filter']['contract_status']))));
            $query = $query->whereIn('contracts.contract_status', $userTypes);
        }
        if(!empty($this->_paramQuery['sort']['id'])) {
            $query = $query->orderBy('contracts.id', $this->_paramQuery['sort']['id']);
        } else if(!empty($this->_paramQuery['sort']['created_at'])) {
            $query = $query->orderBy('contracts.created_at', $this->_paramQuery['sort']['created_at']);
        }
        else if(!empty($this->_paramQuery['sort']['effective_date'])) {
            $query = $query->orderBy('contracts.effective_date', $this->_paramQuery['sort']['effective_date']);
        }else if(!empty($this->_paramQuery['sort']['end_date'])) {
            $query = $query->orderBy('contracts.end_date', $this->_paramQuery['sort']['end_date']);
        }else if(!empty($this->_paramQuery['sort']['updated_at'])) {
            $query = $query->orderBy('contracts.updated_at', $this->_paramQuery['sort']['updated_at']);
        }
        else {
            $query = $query->orderBy('contracts.id', 'DESC');
        }
        return $query;
    }
}
