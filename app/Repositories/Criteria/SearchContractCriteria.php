<?php


namespace App\Repositories\Criteria;


use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;

class SearchContractCriteria implements CriteriaInterface
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
                $join->on('service_packages.id', '=', 'contracts.service_package_id');
            })
            ->leftJoin('companies', function($join) {
                $join->on('companies.id', '=', 'contracts.company_id');
            })
            ->groupBy(['contracts.id','contract_code',
                'companies.company_name','contracts.name_company_rental','service_packages.title',
                'contracts.effective_date','contracts.end_date','contracts.date_signed','contracts.represent_company_hire',
                'contracts.phone_company_hire','contracts.represent_company_rental'	,'contracts.payment_status','contracts.contract_status','contracts.updated_at','contracts.created_at'
            ])->select('contracts.id','contract_code',
                'companies.company_name','contracts.name_company_rental','service_packages.title',
                'contracts.effective_date','contracts.end_date','contracts.date_signed','contracts.represent_company_hire',
                'contracts.phone_company_hire','contracts.represent_company_rental'	,'contracts.payment_status','contracts.contract_status','contracts.updated_at','contracts.created_at');
        if (!empty($this->_paramQuery['search'])) {
            $query = $query-> where(function ($query) {
                $query->where('contracts.id', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('contracts.contract_code', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('contracts.represent_company_hire', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('contracts.address_company_rental', 'LIKE' , '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('contracts.contract_status', 'LIKE' , '%' . $this->_paramQuery['search'] . '%');
            });
        }
        if (!empty($this->_paramQuery['filter']['created_at'])) {
            $created_at = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['created_at']))));
            $query = $query->whereRaw('DATE(sample_contracts.created_at) = ?',$created_at);
        }else if(!empty($this->_paramQuery['filter']['end_date'])) {
            $end_date = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['end_date']))));
            $query = $query->whereIn('contracts.end_date',$end_date);
        }
        else if(!empty($this->_paramQuery['filter']['employee'])){
            $employee = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['employee']))));
            $query = $query->whereIn('contracts.represent_company_rental', $employee);
        }else if(!empty($this->_paramQuery['filter']['company_name_hire'])){
            $company_name_hire = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['company_name_hire']))));
            $query = $query->whereIn('companies.company_name', $company_name_hire);
        }else if(!empty($this->_paramQuery['filter']['contract_status'])){
            $payment_status = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['contract_status']))));
            $query = $query->whereIn('contracts.payment_status', $payment_status);
        }

        if(!empty($this->_paramQuery['sort']['id'])) {
            $query = $query->orderBy('contracts.id', $this->_paramQuery['sort']['id']);
        } else if(!empty($this->_paramQuery['sort']['company_name_hire'])) {
            $query = $query->orderBy('companies.company_name', $this->_paramQuery['sort']['company_name_hire']);
        } else if(!empty($this->_paramQuery['sort']['represent_company_hire'])) {
            $query = $query->orderBy('contracts.represent_company_hire', $this->_paramQuery['sort']['represent_company_hire']);
        } else if(!empty($this->_paramQuery['sort']['created_at'])) {
            $query = $query->orderBy('contracts.created_at', $this->_paramQuery['sort']['created_at']);
        }
        else if(!empty($this->_paramQuery['sort']['effective_date'])) {
            $query = $query->orderBy('contracts.effective_date', $this->_paramQuery['sort']['effective_date']);
        }else if(!empty($this->_paramQuery['sort']['end_date'])) {
            $query = $query->orderBy('contracts.end_date', $this->_paramQuery['sort']['end_date']);
        }else if(!empty($this->_paramQuery['sort']['represent_company_rental'])) {
            $query = $query->orderBy('contracts.represent_company_rental', $this->_paramQuery['sort']['represent_company_rental']);
        }
        else if(!empty($this->_paramQuery['sort']['updated_at'])) {
            $query = $query->orderBy('contracts.	updated_at', $this->_paramQuery['sort']['updated_at']);
        }
        else {
            $query = $query->orderBy('contracts.id', 'DESC');
        }
        return $query;
    }
}
