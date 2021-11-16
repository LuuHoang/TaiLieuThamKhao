<?php

namespace App\Console\Commands;

use App\Constants\ContractStatus;
use App\Mail\ContractExtension;
use App\Repositories\Repository;
use App\Services\WebService\CompanyService;
use App\Services\WebService\ContractContentService;
use Illuminate\Console\Command;
use App\Models\ContractModel;
use Illuminate\Support\Facades\Mail;
class ContractCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contract:update_contract_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Contract_Status and Seed email to user ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $contracts = \App\Models\ContractModel::where('payment_term_all','>',0)->get();
        foreach ($contracts as $contract)
        {
            $arrayData = array();
            $arrayData['payment_term_all'] = $contract->payment_term_all - 1;
            if(($contract->contract_status === ContractStatus::HAS_MADE_A_DEPOSIT) && ($contract->payment_term_all === 1)){
                $arrayData['contract_status'] = ContractStatus::UNFINISHED_PAYMENT;
            }
            if(($contract->contract_status === ContractStatus::TRIAL) && ($contract->payment_term_all === 1)) {
                $arrayData['contract_status'] = ContractStatus::EXPIRED_TRIAL;
            }
            if($contract->contract_status === ContractStatus::FINISH){
                $now = strtotime(now());
                $end_date = strtotime($contract->end_date);
                $datediff = $end_date - $now;
                $days = floor($datediff / (60*60*24));
                if((int)$days === ContractStatus::TIME_BEFORE_END_CONTRACT){
                    $arrayData['contract_status'] = ContractStatus::ALMOST_EXPIRED;
                    $data['id'] = $contract->id;
                    $data['company'] = $contract->company->company_name;
                    $data['end_date'] = date('d/m/Y',strtotime($contract->end_date));
                    $data['employee'] = $contract->admin->full_name;
                    $data['phone_company_hire'] = $contract->phone_company_hire;
                    $data['represent_company_hire'] = $contract->represent_company_hire;
                    Mail::to($contract->admin->email)->send(new ContractExtension($data));
                }
            }
            $contract->update($arrayData);
        }
    }
}
