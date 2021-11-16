<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\AdminCompanyCreateUserRequest;
use App\Http\Requests\WebRequests\AdminCompanyDeleteUserRequest;
use App\Http\Requests\WebRequests\AdminCompanyUpdateUserRequest;
use App\Http\Requests\WebRequests\ImportUserRequest;
use App\Http\Requests\WebRequests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\FailureImportUserResponse;
use App\Http\Resources\ListUserForAdminCompanyResource;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserDetailResourceV2;
use App\Services\ImportExportService;
use App\Services\WebService\CompanyService;
use App\Services\WebService\UserService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class AdminCompanyController extends Controller
{
    private $_companyService;
    private $_userService;
    private $_importExportService;

    public function __construct(CompanyService $companyService, UserService $userService, ImportExportService $importExportService)
    {
        $this->_companyService = $companyService;
        $this->_userService = $userService;
        $this->_importExportService = $importExportService;
    }

    public function updateCompany(UpdateCompanyRequest $request, int $companyId)
    {
        $companyData = $request->only('company_name', 'address', 'color', 'logo');
        $company = $this->_companyService->updateCompany($companyData, $companyId);
        return Response::success([
            'company_data'  =>  new CompanyResource($company)
        ]);
    }

    public function getUser(int $userId)
    {
        $user = $this->_userService->getUser($userId);
        return Response::success([
            'user_data' =>  new UserDetailResourceV2($user)
        ]);
    }

    public function getListUser(Request $request)
    {
        $user = app('currentUser');
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort', 'filter']);
        $listUser = $this->_userService->getListUser($user->company_id, $paramQuery, $limit);
        return Response::pagination(
            ListUserForAdminCompanyResource::collection($listUser),
            $listUser->total(),
            $listUser->currentPage(),
            $limit
        );
    }

    public function createUser(AdminCompanyCreateUserRequest $request)
    {
        $currentUser = app('currentUser');
        $data = $request->only('staff_code', 'full_name', 'email', 'address', 'password', 'department', 'position', 'role_id', 'avatar_path', 'user_created_id');
        $user = $this->_userService->createUser($data, $currentUser->company_id);
        return Response::success([
            'user_data' =>  new UserDetailResource($user)
        ]);
    }

    public function updateUser(AdminCompanyUpdateUserRequest $request, int $userId)
    {
        $companyId = $currentUser = app('currentUser')->company_id;
        $data = $request->only('staff_code', 'full_name', 'email', 'address', 'password', 'department', 'position', 'role_id', 'avatar_path', 'extend_size', 'user_created_id');
        $user = $this->_userService->updateUserForAdmin($data, $companyId, $userId);
        return Response::success([
            'user_data'  =>  new UserDetailResource($user)
        ]);
    }

    public function deleteUser(AdminCompanyDeleteUserRequest $request, int $userId)
    {
        $userTargetId = (int)$request->user_target_id;
        $this->_userService->deleteUserForAdminCompany($userId, $userTargetId);
        return Response::success();
    }

    public function exportUsers(Request $request)
    {
        $companyId = $currentUser = app('currentUser')->company_id;
        $url = $this->_importExportService->exportUsers($companyId);
        return Response::success([
            'url' => $url
        ]);
    }

    public function importUsers(ImportUserRequest $request)
    {
        $companyId = $currentUser = app('currentUser')->company_id;
        $file = $request->file;
        $failures = $this->_importExportService->importUsers($companyId, $file);
        return Response::success([
            'failures' => FailureImportUserResponse::collection($failures)
        ]);
    }
}
