<?php


namespace App\Http\Controllers\WebControllers;


use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\CreateSampleContractPropertyRequest;
use App\Http\Requests\WebRequests\CreateSampleContractRequest;
use App\Http\Resources\ContractResource;
use App\Http\Resources\ListCompanyResource;
use App\Http\Resources\ListAllSampleContractResource;
use App\Http\Resources\SampleContractResource;
use App\Http\Resources\ShortCompanyDetailResource;
use App\Http\Resources\ShortContractDetailResource;
use App\Services\AdminInformationService;
use App\Services\WebService\ContractService;
use App\Services\WebService\SampleContractPropertyService;
use App\Services\WebService\SampleContractService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class AdminSampleContractController extends Controller
{
    private $_sampleContractService;
    private $_sampleContractPropertyService;
    private $_adminInformationService;
    public function __construct(SampleContractService $sampleContractService,SampleContractPropertyService $sampleContractPropertyService,AdminInformationService $adminInformationService)
    {
        $this->_sampleContractService = $sampleContractService;
        $this->_sampleContractPropertyService=$sampleContractPropertyService;
        $this->_adminInformationService = $adminInformationService;
    }
    public function getSampleContract(int $sampleContractId)
    {
        $sampleContract=$this->_sampleContractService->getSampleContract($sampleContractId);
        return Response::success([
            'sample_contract'  =>  new SampleContractResource($sampleContract)
        ]);
    }
    public function createSampleContract(CreateSampleContractRequest $request)
    {

        $sampleContractData = $request->only('name_contract','description','tags','category','content');
        $sampleContractPropertyData = $request->input('sample_contract_properties');
        $sampleContract = $this->_sampleContractService->createSampleContract($sampleContractData);
        if($sampleContractPropertyData){
            $this->_sampleContractPropertyService->createSampleContractProperty($sampleContractPropertyData,$sampleContract->id);
        }
        return Response::success([
            'sample_contract'  =>  new SampleContractResource($sampleContract)
        ]);
    }
    public function updateSampleContract(CreateSampleContractRequest $request,int $sampleContractId){
        $sampleContractData = $request->only('name_contract','description','tags','category','content');
        $sampleContractPropertyData = $request->input('sample_contract_properties');
        $sampleContract = $this->_sampleContractService->updateSampleContract($sampleContractData,$sampleContractId);
        if($sampleContractPropertyData) {
            $this->_sampleContractPropertyService->updateOrCreateSampleContractProperties($sampleContractPropertyData, $sampleContract->id);
        }
        return Response::success([
            'sample_contract'  =>  new SampleContractResource($sampleContract)
        ]);
    }
    public function  deleteSampleContract(int $sampleContractId)
    {

        $this->_sampleContractPropertyService->deleteSampleContractProperties($sampleContractId);
        $this->_sampleContractService->deleteSampleContract($sampleContractId);
        return Response::success();
    }
    public function getListSampleContract()
    {
        $sampleContracts=$this->_sampleContractService->getListSampleContract();
        return Response::success([
            'list_sample_contract'  =>  ListAllSampleContractResource::collection($sampleContracts)
        ]);
    }
    public function getListShortCode()
    {
        $shortCode = $this->_adminInformationService->getListShortCode();
        return Response::success([
            'company'  => new ShortCompanyDetailResource($shortCode),
            'contract' => new ShortContractDetailResource($shortCode)
        ]);
    }
}
