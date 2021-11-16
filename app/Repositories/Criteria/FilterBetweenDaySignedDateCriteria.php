<?php


namespace App\Repositories\Criteria;


use App\Constants\CategorySampleContract;
use App\Constants\ContractStatus;
use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;
use Carbon\Carbon;

class FilterBetweenDaySignedDateCriteria implements CriteriaInterface
{
    public $_paramStart;
    public $_paramEnd;

    public function __construct(Carbon $paramStart ,Carbon $paramEnd)
    {
        $this->_paramStart = $paramStart;
        $this->_paramEnd = $paramEnd;
    }

    /**
     * @inheritDoc
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model->
        leftJoin('sample_contracts', function($join) {
            $join->on('sample_contracts.id', '=', 'contracts.sample_contract_id');
        })
            ->groupBy([
                'contracts.company_id','contracts.service_package_id','contracts.id','contracts.contract_status','contracts.date_signed','sample_contracts.category',
            ])->select(
                'contracts.company_id','contracts.service_package_id','contracts.id','contracts.contract_status','contracts.date_signed','sample_contracts.category');
        $model = $query->where(function ($query) {
            $query->whereDate('contracts.date_signed', '>=', $this->_paramStart->toDateString())
                ->whereDate('contracts.date_signed', '<=', $this->_paramEnd->toDateString())
                ->whereIn('contracts.contract_status' , ContractStatus::signed())
                ->whereIn('sample_contracts.category',CategorySampleContract::notTrial())
            ;
        });
        return $model;
    }
}
