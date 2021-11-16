<?php


namespace App\Repositories\Criteria;


use App\Constants\CategorySampleContract;
use App\Constants\ContractStatus;
use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FilterServicePackageBySignedDateCriteria implements CriteriaInterface
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
        $query = $model->leftJoin('sample_contracts', function($join) {
            $join->on('sample_contracts.id', '=', 'contracts.sample_contract_id');
        })->leftJoin('service_packages', function($join) {
            $join->on('service_packages.id', '=', 'contracts.service_package_id');
        })->groupBy([
            'service_packages.id',
        ])->select([
            'service_packages.id as service_package_id',
            DB::raw('SUM(`service_packages`.`max_user`) as sum_user'),
            DB::raw('SUM(`service_packages`.`max_user_data`) as sum_data'),
            DB::raw('COUNT(`contracts`.`company_id`) as companies_use_total'),
        ]);
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
