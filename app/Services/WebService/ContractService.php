<?php


namespace App\Services\WebService;


use App\Constants\ContractStatus;
use App\Constants\TypePackageService;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Http\Requests\WebRequests\CreateCompanyRequest;
use App\Models\ContractModel;
use App\Models\CompanyModel;
use App\Models\AdminModel;
use App\Models\ExtendPackageModel;
use App\Models\SampleContractModel;
use App\Models\ServicePackageModel;
use App\Repositories\Criteria\SearchContractByCompanyCriteria;
use App\Repositories\Criteria\SearchContractCriteria;
use App\Repositories\Repository;
use App\Services\AbstractService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
class ContractService extends AbstractService
{
    protected $_contractRepository;
    protected $_companyService;
    protected $_contractContentService;
    public function __construct(
        ContractModel $contractModel,
        CompanyService $companyService,
        ContractContentService $contractContentService
    )
    {
        $this->_contractRepository = new Repository($contractModel);
        $this->_companyService =$companyService;
        $this->_contractContentService = $contractContentService;
    }
    public function createContract(array $arrayData,array $arrayCompany,array $content)
    {
        $admin =app('currentAdmin')->id;
        try {
            $arrayData['effective_date'] = date('Y-m-d',strtotime($arrayData['effective_date']));
            $arrayData['end_date'] = date('Y-m-d',strtotime($arrayData['end_date']));
            $arrayData['date_signed'] = date('Y-m-d',strtotime($arrayData['date_signed']));
            $idSampleContracts = SampleContractModel::all()->pluck('id')->toArray();
            if (!in_array($arrayData['sample_contract_id'], $idSampleContracts)) {
                throw new NotFoundException('messages.not_found_with_sample_contract_id');
            }
            $idServicePackages = ServicePackageModel::all()->pluck('id')->toArray();
            if (!in_array($arrayData['service_package_id'], $idServicePackages)) {
                throw new NotFoundException('messages.not_found_with_service_package_id');
            }
            $idAdmin = AdminModel::all()->pluck('id')->toArray();
            if (!in_array($arrayData['employee_represent'], $idAdmin)) {
                throw new NotFoundException('messages.not_found_with_employee_represent');
            }
            $contract_codes = ContractModel::all()->pluck('contract_code')->toArray();
            $arrayData['contract_code'] = $this->generateUniqueID($contract_codes);
            if (isset($arrayCompany['company_id'])) {
                $arrayData['company_id'] = (int)$arrayData['company_id'];
                $idCompanies = CompanyModel::all()->pluck('id')->toArray();
                if (!in_array($arrayCompany['company_id'], $idCompanies)) {
                    throw new NotFoundException('messages.not_found_with_company_id');
                }
                 $dataCreated = $this->create($arrayData,$arrayCompany['company_id'],$admin);
                foreach ($content as $item) {
                    $this->_contractContentService->createContractContentService($dataCreated->id,$item['sample_contract_property_id'],$item['content']);
                }
                 return $dataCreated;
            }
            $now = mktime(0, 0, 0, date("m")+1, date("d"));
            $arrayData['end_date'] = date('Y-m-d',$now);
            $company = $this->createCompany($arrayCompany);
            $dataCreated = $this->create($arrayData,$company->id,$admin);
            foreach ($content as $item) {
                $this->_contractContentService->createContractContentService($dataCreated->id,$item['sample_contract_property_id'],$item['content']);
            }
            return $dataCreated;
        } catch (NotFoundException $exception) {
            report($exception);
            throw $exception;
        }catch (\Exception $exception){
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    public function updateContract(array $arrayData,array $content,int $contractId)
    {
        $admin =app('currentAdmin')->id;
        try {
            if(isset($arrayData['effective_date'])){
                $arrayData['effective_date'] = date('Y-m-d',strtotime($arrayData['effective_date']));
            }
            if(isset($arrayData['end_date'])){
                $arrayData['end_date'] = date('Y-m-d',strtotime($arrayData['end_date']));
            }
            if(isset($arrayData['date_signed'])){
                $arrayData['date_signed'] = date('Y-m-d',strtotime($arrayData['date_signed']));
            }
            if(isset($arrayData['sample_contract_id'])){
                $idSampleContracts = SampleContractModel::all()->pluck('id')->toArray();
                if (!in_array($arrayData['sample_contract_id'], $idSampleContracts)) {
                    throw new NotFoundException('messages.not_found_with_sample_contract_id');
                }
            }
            if(isset($arrayData['service_package_id']))
            {
                $idServicePackages = ServicePackageModel::all()->pluck('id')->toArray();
                if (!in_array($arrayData['service_package_id'], $idServicePackages)) {
                    throw new NotFoundException('messages.not_found_with_service_package_id');
                }
            }
            if(isset($arrayData['employee_represent'])){
                $idAdmin = AdminModel::all()->pluck('id')->toArray();
                if (!in_array($arrayData['employee_represent'], $idAdmin)) {
                    throw new NotFoundException('messages.not_found_with_employee_represent');
                }
            }
            if(isset($arrayCompany['company_id'])) {
                $arrayData['company_id'] = (int)$arrayData['company_id'];
                $idCompanies = CompanyModel::all()->pluck('id')->toArray();
                if (!in_array($arrayCompany['company_id'], $idCompanies)) {
                    throw new NotFoundException('messages.not_found_with_company_id');
                }
            }
            if(isset($arrayData['contract_status'])){
                if($arrayData['contract_status'] === ContractStatus::TRIAL){
                    $now = mktime(0, 0, 0, date("m")+1, date("d"));
                    $arrayData['end_date'] = date('Y-m-d',$now);
                }
            }
            $dataUpdated = $this->update($arrayData,$admin,$contractId);
            $this->_contractContentService->updateContractContentService($content,$contractId);
            return $dataUpdated;
        } catch (NotFoundException $exception) {
            report($exception);
            throw $exception;
        }catch (\Exception $exception){
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    public function getContractInfo(int $contractId)
    {
        $contract= $this->_contractRepository->find($contractId,['contract_code','id','sample_contract_id','company_id','represent_company_hire','phone_company_hire','gender_hire','name_company_rental','address_company_rental','represent_company_rental','gender_rental','phone_number_rental','service_package_id','extend_package_id','type_service_package','tax','total_price','payment_status','effective_date','end_date','cancellation_notice_deadline','deposit_money','payment_term_all','employee_represent','contract_status','date_signed','created_by','updated_at','created_at','deleted_at']);
        if (!$contract) {
            throw new NotFoundException('messages.not_found_with_contract_id');
        }
        return $contract;
    }
    public function getContractInfoByCompanyId(int $companyId)
    {
        $contract = $this->_contractRepository->where('company_id' ,'=',$companyId)
            ->whereIn('contract_status',ContractStatus::active())
                ->first();
        if (!$contract) {
            throw new NotFoundException('messages.not_found_with_company_id');
        }
        return $contract;
    }
    public function getListContractInfoByCompanyId(int $companyId)
    {
        $contract = $this->_contractRepository->where('company_id' ,'=',$companyId)->all();
        if (!$contract) {
            throw new NotFoundException('messages.not_found_with_company_id');
        }
        return $contract;
    }
    public function create(array $arrayData ,int $companyId,int $admin){
        if((int)$arrayData['type_service_package'] === TypePackageService::INVARIANT){
            $arrayData['extend_package_id'] = null;
        }
        if((int)$arrayData['type_service_package'] === TypePackageService::EXTEND){
            $arrayData['extend_package_id'] = (int) $arrayData['extend_package_id'];
            $idExtendPacks = ExtendPackageModel::all()->pluck('id')->toArray();
            if (!in_array($arrayData['extend_package_id'], $idExtendPacks)) {
                throw new NotFoundException('messages.not_found_with_extend_package_id');
            }
        }
        if(!isset($arrayData['additional_content'])){
            $arrayData['additional_content'] = null;
        }
        return $this->_contractRepository->create([
            'sample_contract_id' =>  (int)$arrayData['sample_contract_id'],
            'company_id' => $companyId,
            'contract_code' => $arrayData['contract_code'],
            'represent_company_hire' => $arrayData['represent_company_hire'],
            'phone_company_hire' => $arrayData['phone_company_hire'],
            'gender_hire' =>  (int)$arrayData['gender_hire'],
            'name_company_rental' => $arrayData['name_company_rental'],
            'address_company_rental' => $arrayData['address_company_rental'],
            'represent_company_rental' => $arrayData['represent_company_rental'],
            'gender_rental' => (int)$arrayData['gender_rental'],
            'phone_number_rental' => $arrayData['phone_number_rental'],
            'service_package_id' =>  (int)$arrayData['service_package_id'],
            'type_service_package' =>  (int)$arrayData['type_service_package'],
            'extend_package_id'    =>  $arrayData['extend_package_id'],
            'tax' =>  (double)$arrayData['tax'],
            'total_price' =>  (double)$arrayData['total_price'],
            'payment_status' => (int)$arrayData['payment_status'],
            'effective_date' => $arrayData['effective_date'],
            'end_date' => $arrayData['end_date'],
            'cancellation_notice_deadline' =>  (int)$arrayData['cancellation_notice_deadline'],
            'deposit_money' =>  (double)$arrayData['deposit_money'],
            'payment_term_all' =>  (int)$arrayData['payment_term_all'],
            'employee_represent' =>  (int)$arrayData['employee_represent'],
            'contract_status' =>  (int)$arrayData['contract_status'],
            'date_signed' => $arrayData['date_signed'],
            'created_by' => $admin
        ]);

    }
    public function createCompany(array $arrayCompany)
    {
        $arrayCompany['service_id'] =(int) $arrayCompany['service_id'];
        if(isset($arrayCompany['extend_id'])){
            $arrayCompany['extend_id'] =(int) $arrayCompany['extend_id'];
        }
        return $this->_companyService->companyCreationAtAddingContract($arrayCompany);
    }
    public function getListContracts(int $limit, Array $paramQuery): LengthAwarePaginator
    {
        return $this->_contractRepository
            ->pushCriteria(new SearchContractCriteria($paramQuery))
            ->with(['servicePackage','company'])
            ->paginate($limit);
    }
    public function getListContractsByCompanyId(int $companyId,int $limit, Array $paramQuery): LengthAwarePaginator
    {
        $paramQuery['company'] = $companyId;
        return $this->_contractRepository
            ->pushCriteria(new SearchContractByCompanyCriteria($paramQuery))
            ->paginate($limit);
    }
    public function retrieveContractInformationToLogin(int $company_id)
    {
        $contract= $this->_contractRepository->where('company_id','=',$company_id)->first();
        if (!$contract) {
            throw new NotFoundException('messages.not_found_with_contract_id');
        }
        return $contract;
    }
    public function generateUniqueID(array $ids)
    {
        $permittedChars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $idRandom = '';
        for ($i = 0; $i < 10; $i++) {
            $idRandom .= $permittedChars[rand(0, strlen($permittedChars) - 1)];
        }
        if (in_array($idRandom, $ids)) {
            $idRandom = $this->generateUniqueID($ids);
        }
        return $idRandom;
    }
    public function update(array $arrayData,int $admin,int $contractId)
    {
        $arrayData['updated_by'] = $admin;
        $contract = $this->_contractRepository->find($contractId);
        $contract->update($arrayData);
        return $contract;
    }
    public function getLongestContractByCompany(int $companyId){
        $contract = $this->_contractRepository->where('company_id' ,'=',$companyId)
            ->whereIn('contract_status',ContractStatus::active())
            ->orderBy('end_date','DESC')->first();

        return $contract;
    }
}
