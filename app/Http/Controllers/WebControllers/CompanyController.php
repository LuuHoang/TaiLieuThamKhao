<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\AdminSCSoftUpdateCompanyRequest;
use App\Http\Requests\WebRequests\CreateCompanyRequest;
use App\Http\Resources\CompanyDetailResource;
use App\Http\Resources\CompanyResource;
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

    public function getCompanyCurrentUser(Request $request)
    {
        $companyId = app('currentUser')->company_id;
        $company = $this->_companyService->getCompany($companyId);
        return Response::success([
            'company_data'  =>  new CompanyResource($company)
        ]);
    }

    public function createCompany(CreateCompanyRequest $request)
    {
        $company = $this->_companyService->createCompany($request);
        return Response::success([
            'company_data'  =>  new CompanyDetailResource($company)
        ]);
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

    public function getCompany(int $companyId)
    {
        $company = $this->_companyService->getCompany($companyId);
        return Response::success([
            'company_data'  =>  new CompanyDetailResource($company)
        ]);
    }

    public function updateCompany(AdminSCSoftUpdateCompanyRequest $request, int $companyId)
    {
        $dataUpdate = $request->only('company_name', 'company_code', 'address', 'representative', 'tax_code', 'service_id', 'extend_id', 'color', 'logo');
        $company = $this->_companyService->updateCompanyForAdminSCSoft($dataUpdate, $companyId);
        return Response::success([
            'company_data'  =>  new CompanyDetailResource($company)
        ]);
    }

    public function deleteCompany(int $companyId)
    {
        $this->_companyService->deleteCompany($companyId);
        return Response::success();
    }
}
