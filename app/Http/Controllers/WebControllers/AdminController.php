<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\AddAdminRequest;
use App\Http\Requests\WebRequests\UpdateAdminRequest;
use App\Http\Resources\AdminCreatedResource;
use App\Http\Resources\AdminResource;
use App\Http\Resources\ListAdminResource;
use App\Http\Resources\SampleContractPropertyResource;
use App\Http\Resources\ShortCodeAdminCompanyResource;
use App\Services\AdminService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private $_adminService;

    public function __construct(AdminService $adminService)
    {
        $this->_adminService = $adminService;
    }

    public function verifyAdmin()
    {
        $currentAdmin = app('currentAdmin');
        $companyData = $this->_adminService->getCompanyData();
        return Response::success([
            'admin_data' => new AdminResource($currentAdmin),
            'company_data' => $companyData
        ]);
    }

    public function logout(Request $request)
    {
        $bearerToken = $request->bearerToken();
        $this->_adminService->logout($bearerToken);
        return Response::success();
    }
    public function getListUserAreAdmin()
    {
        $listUsers=$this->_adminService->getListUserAreAdmin();
        return Response::success([
            'list_users'  =>   ListAdminResource::collection($listUsers)
        ]);
    }
    public function createAdmin(AddAdminRequest $request){
        $dataAdmin = $request->only('full_name','email','password');
        $dataAdmin['avatar_path'] = $request->file('avatar');
        $adminCreated = $this->_adminService->createAdmin($dataAdmin);
        return Response::success([
           'admin' => new AdminCreatedResource($adminCreated)
        ]);
    }
    public function updateAdmin(UpdateAdminRequest $request,int $adminId)
    {
        $dataAdmin = $request->only('full_name','email','password');
        $dataAdmin['avatar_path'] = $request->file('avatar');
        $adminUpdated = $this->_adminService->updateAdmin($dataAdmin,$adminId);
        return Response::success([
            'admin' => new AdminCreatedResource($adminUpdated)
        ]);
    }
    public function deleteAdmin(int $adminId,int $responsibleAdminId)
    {
        $this->_adminService->deleteAdmin($adminId,$responsibleAdminId);
        return Response::success([

        ]);
    }
    public function getListAdmin(Request $request){
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort', 'filter']);
        $admins = $this->_adminService->getListAdmin($limit, $paramQuery);
        return Response::pagination(
            AdminCreatedResource::collection($admins),
            $admins->total(),
            $admins->currentPage(),
            $limit
        );
    }
}
