<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\App;
use App\Constants\Disk;
use App\Exceptions\SystemException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\AdminSCSoftCreateUserRequest;
use App\Http\Requests\WebRequests\AdminSCSoftUpdateUserRequest;
use App\Http\Requests\WebRequests\AdminSCSoftUploadAvatarRequest;
use App\Http\Requests\WebRequests\CreateOrUpdateLinkVersionRequest;
use App\Http\Resources\LinkVersionDetailResource;
use App\Http\Resources\ListRoleResource;
use App\Http\Resources\ListUserResource;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserDetailResourceV2;
use App\Services\UploadService;
use App\Services\WebService\LinkVersionService;
use App\Services\WebService\UserService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class AdminSCSoftController extends Controller
{
    private $_userService;
    private $_uploadService;
    private $_linkVersionService;

    public function __construct(UserService $userService, UploadService $uploadService, LinkVersionService $linkVersionService)
    {
        $this->_userService = $userService;
        $this->_uploadService = $uploadService;
        $this->_linkVersionService = $linkVersionService;
    }

    public function createUser(AdminSCSoftCreateUserRequest $request)
    {
        $companyId = $request->company_id;
        $data = $request->only('staff_code', 'full_name', 'email', 'address', 'password', 'department', 'position', 'role_id', 'avatar_path', 'user_created_id');
        $user = $this->_userService->createUser($data, $companyId);
        return Response::success([
            'user_data'  =>  new UserDetailResource($user)
        ]);
    }

    public function getUser(int $userId)
    {
        $user = $this->_userService->getUser($userId);
        return Response::success([
            'user_data'  =>  new UserDetailResourceV2($user)
        ]);
    }

    public function deleteUser(int $userId)
    {
        $this->_userService->deleteUser($userId);
        return Response::success();
    }

    public function updateUser(AdminSCSoftUpdateUserRequest $request, int $userId)
    {
        $data = $request->only('staff_code', 'full_name', 'email', 'address', 'password', 'department', 'position', 'role_id', 'avatar_path', 'extend_size', 'user_created_id');
        $user = $this->_userService->updateUserForAdminSCSoft($data, $userId);
        return Response::success([
            'user_data'  =>  new UserDetailResource($user)
        ]);
    }

    public function getAllUser(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort', 'filter']);
        $users = $this->_userService->getAllUser($limit, $paramQuery);

        return Response::pagination(
            ListUserResource::collection($users),
            $users->total(),
            $users->currentPage(),
            $limit
        );
    }

    public function uploadAvatar(AdminSCSoftUploadAvatarRequest $request)
    {
        $adminId = app('currentAdmin')->id;
        $file = $request->file('file');
        $image = $this->_uploadService->uploadAvatar($file, $adminId);
        return Response::success([
            'admin_id' => $adminId,
            'image_path' => $image,
            'image_url' => Storage::disk(Disk::USER)->url($image)
        ]);
    }

    public function getListCompanyRole(Request $request, int $companyId)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'filter']);
        $roles = $this->_userService->getAllRole($limit, $companyId, $paramQuery);
        return Response::pagination(
            ListRoleResource::collection($roles),
            $roles->total(),
            $roles->currentPage(),
            $limit
        );
    }

    public function getLinkVersion()
    {
        $result = $this->_linkVersionService->getLinkVersion();
        return Response::success([
            'links' => new LinkVersionDetailResource($result)
        ]);
    }

    public function createOrUpdateVersionLink(CreateOrUpdateLinkVersionRequest $request)
    {
        $data = $request->only('ios', 'android');
        $this->_linkVersionService->CreateOrUpdateLinkVersion($data);
        return Response::success();
    }
}

