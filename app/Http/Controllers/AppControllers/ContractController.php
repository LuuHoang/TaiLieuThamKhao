<?php


namespace App\Http\Controllers\AppControllers;


use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminCreatedResource;
use App\Http\Resources\ContractResource;
use App\Http\Resources\ListCompanyResource;
use App\Http\Resources\ListContractResource;
use App\Services\WebService\ContractService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    private $_contractService;

    public function __construct(ContractService $contractService)
    {
        $this->_contractService = $contractService;
    }
    public function getListContract()
    {
        $companyId = app('currentUser')->company_id;
        $contracts = $this->_contractService->getListContractInfoByCompanyId($companyId);
        return Response::success([
            'list_contracts' => ListContractResource::collection($contracts)
        ]);
    }
    public function getListContractsPaging(Request $request){
        $companyId = app('currentUser')->company_id;
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort', 'filter']);
        $contracts = $this->_contractService->getListContractsByCompanyId($companyId,$limit, $paramQuery);
        return Response::pagination(
            ListContractResource::collection($contracts),
            $contracts->total(),
            $contracts->currentPage(),
            $limit
        );
    }
}
