<?php


namespace App\Http\Controllers\AppControllers;


use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Resources\ListCompanyResource;
use App\Services\WebService\CompanyService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private $_companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->_companyService = $companyService;
    }
    public function getListCompanies(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort', 'filter']);
        $companies = $this->_companyService->getCompaniesList($limit, $paramQuery);

        return Response::pagination(
            ListCompanyResource::collection($companies),
            $companies->total(),
            $companies->currentPage(),
            $limit
        );
    }
    public function getCompanyInfo()
    {
        $company_id =app('currentUser')->company_id;
        $companyInfo = $this->_companyService->getCompany($company_id);
        return Response::success([
           'company' => new ListCompanyResource($companyInfo)
        ]);
    }
}
