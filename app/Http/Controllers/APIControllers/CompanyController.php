<?php

namespace App\Http\Controllers\APIControllers;

use App\Exceptions\UnprocessableException;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Services\APIServices\CompanyService;
use App\Supports\Facades\Response\Response;

class CompanyController extends Controller
{

    private $_companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->_companyService = $companyService;
    }

    public function getCompanyByCompanyCode(String $companyCode)
    {
        if (strlen($companyCode) < 6)
            throw new UnprocessableException('messages.company_code_least_6_characters');

        $result = $this->_companyService->getCompanyByCompanyCode($companyCode);

        return Response::success([
            'company_data' => new CompanyResource($result)
        ]);
    }
}
