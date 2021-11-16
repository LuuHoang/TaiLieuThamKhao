<?php


namespace App\Http\Controllers\WebControllers;


use App\Constants\App;
use App\Constants\ContractStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\CreateContractRequest;
use App\Http\Requests\WebRequests\UpdateContractRequest;
use App\Http\Resources\ContractResource;
use App\Http\Resources\ListSampleContractResource;
use App\Http\Resources\ListContractResource;
use App\Services\WebService\ContractService;
use App\Services\WebService\SampleContractService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;


class AdminContractController extends Controller
{
    private $_contractService;
    private $_sampleContractService;
    public function __construct(ContractService $contractService,SampleContractService $sampleContractService)
    {
        $this->_contractService=$contractService;
        $this->_sampleContractService=$sampleContractService;
    }
    public function createContract(CreateContractRequest $request)
    {
        $arrayCompany = $request->only('company_name','company_code','company_id','address','tax_code','representative');
        $arrayCompany['service_id'] = $request->only('service_package_id');
        $arrayCompany['extend_id']  = $request->only('extend_package_id');
        $arrayContract = $request->only('sample_contract_id','represent_company_hire','phone_company_hire','gender_hire'
            ,'name_company_rental','address_company_rental','represent_company_rental','gender_rental','phone_number_rental'
            ,'service_package_id','type_service_package','tax','total_price','payment_status','effective_date','end_date'
            ,'cancellation_notice_deadline','deposit_money','payment_term_all','employee_represent','contract_status','extend_package_id','date_signed','company_id');
        $content = $request->input('additional_content');
        $contractCreated = $this->_contractService->createContract($arrayContract,$arrayCompany,$content);
        return Response::success([
            'contract'  =>  new ContractResource($contractCreated)
        ]);
    }
    public function updateContract(UpdateContractRequest $request,int $contractId){
        $arrayCompany['service_id'] = $request->only('service_package_id');
        $arrayCompany['extend_id']  = $request->only('extend_package_id');
        $arrayContract = $request->only('sample_contract_id','represent_company_hire','phone_company_hire','gender_hire'
            ,'name_company_rental','address_company_rental','represent_company_rental','gender_rental','phone_number_rental'
            ,'service_package_id','type_service_package','tax','total_price','payment_status','effective_date','end_date'
            ,'cancellation_notice_deadline','deposit_money','payment_term_all','employee_represent','contract_status','extend_package_id','date_signed','company_id');
        $content = $request->input('additional_content');
        $contractCreated = $this->_contractService->updateContract($arrayContract,$content,$contractId);
        return Response::success([
            'contract'  =>  new ContractResource($contractCreated)
        ]);
    }
    public function getContractInfo(int $contractId)
    {
        $contractCreated=$this->_contractService->getContractInfo($contractId);
        return Response::success([
            'contract'  =>  new ContractResource($contractCreated)
        ]);
    }
    public function getListSampleContracts(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort', 'filter']);
        $sampleContracts = $this->_sampleContractService->getListSampleContracts($limit, $paramQuery);
        return Response::pagination(
            ListSampleContractResource::collection($sampleContracts),
            $sampleContracts->total(),
            $sampleContracts->currentPage(),
            $limit
        );
    }
    public function getListContracts(Request $request){
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort', 'filter']);
        $contracts = $this->_contractService->getListContracts($limit, $paramQuery);
        return Response::pagination(
            ListContractResource::collection($contracts),
            $contracts->total(),
            $contracts->currentPage(),
            $limit
        );
    }
    public function getListContractsByCompany($companyId)
    {
        $contracts = $this->_contractService->getListContractInfoByCompanyId($companyId);
        return Response::success([
             'contracts' => ListContractResource::collection($contracts)
            ]);
    }
    public function getListContractsByCurrentUsers(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort', 'filter']);
        $companyId = app('currentUser')->company_id;
        $contracts = $this->_contractService->getListContractsByCompanyId($companyId,$limit,$paramQuery);
        return Response::pagination(
            ListContractResource::collection($contracts),
            $contracts->total(),
            $contracts->currentPage(),
            $limit
        );
    }
}
