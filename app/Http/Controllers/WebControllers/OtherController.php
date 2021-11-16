<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ListPDFFormatResource;
use App\Http\Resources\ListRoleResource;
use App\Services\OtherService;
use App\Services\WebService\AlbumPDFService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Collection;

class OtherController extends Controller
{
    private $_otherService;
    private $_albumPDFService;

    public function __construct(OtherService $otherService, AlbumPDFService $albumPDFService)
    {
        $this->_otherService = $otherService;
        $this->_albumPDFService = $albumPDFService;
    }

    public function getDepartments()
    {
        $departments = $this->_otherService->getDepartments();
        return Response::success([
            "departments"   =>  $departments
        ]);
    }

    public function getPositions()
    {
        $positions = $this->_otherService->getPositions();
        return Response::success([
            "positions"   =>  $positions
        ]);
    }

    public function getServicePackages()
    {
        $servicePackages = $this->_otherService->getServicePackages();
        return Response::success([
            "service_packages"   =>  $servicePackages
        ]);
    }

    public function getExtendPackages()
    {
        $extendPackages = $this->_otherService->getExtendPackages();
        return Response::success([
            "extend_packages"   =>  $extendPackages
        ]);
    }

    public function getListCompanies()
    {
        $companies = $this->_otherService->getListCompanies();
        return Response::success([
            "companies"   =>  $companies
        ]);
    }

    public function getListUsers()
    {
        $users = $this->_otherService->getListUsers();
        return Response::success([
            "users"   =>  $users
        ]);
    }

    public function getListAllUsers()
    {
        $users = $this->_otherService->getListAllUsers();
        return Response::success([
            "users"   =>  $users
        ]);
    }

    public function getDashboard(Request $request)
    {
        $paramQuery = $request->all(['start_day', 'end_day']);
        $result = $this->_otherService->getDashboard($paramQuery);
        return Response::success([
            "dashboard"   =>  $result
        ]);
    }

    public function getDashboardForAdmin(Request $request)
    {
        $paramQuery = $request->only(['start_day', 'end_day']);
        $result = $this->_otherService->getDashboardForAdmin($paramQuery);
        return Response::success([
            "dashboard"   =>  $result
        ]);
    }

    public function retrieveListRoleInCompany(int $companyId)
    {
        $roles = $this->_otherService->retrieveListRoleInCompany($companyId);
        return Response::success([
            "roles"   =>  ListRoleResource::collection($roles)
        ]);
    }

    public function getListUsersOfCompany(int $companyId)
    {
        $users = $this->_otherService->getListUsersOfCompany($companyId);
        return Response::success([
            "users"   =>  $users
        ]);
    }

    public function getListPDFContentTemplates()
    {
        $templates = $this->_otherService->getListPDFContentTemplates();
        return Response::success([
            "templates"   =>  $templates
        ]);
    }

    public function getListPDFFormat(){
        $result = $this->_albumPDFService->getListAlbumPDFFormats();
        return Response::success([
           "formats"    =>  ListPDFFormatResource::collection($result)
        ]);
    }
}
