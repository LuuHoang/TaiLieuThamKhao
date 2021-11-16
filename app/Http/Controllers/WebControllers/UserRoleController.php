<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\CreateRoleRequest;
use App\Http\Requests\WebRequests\UpdateRoleRequest;
use App\Http\Resources\ListRoleResource;
use App\Http\Resources\RoleDetailResource;
use App\Services\WebService\UserRoleService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    protected $userRoleService;

    public function __construct(UserRoleService $userRoleService)
    {
        $this->userRoleService = $userRoleService;
    }

    public function retrieveListRoles(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $queryParam = $request->all(['search']);
        $result = $this->userRoleService->retrieveListRoles($queryParam, $limit);
        return Response::pagination(
            ListRoleResource::collection($result),
            $result->total(),
            $result->currentPage(),
            $limit
        );
    }

    public function retrieveRoleDetail(int $roleId)
    {
        $result = $this->userRoleService->retrieveRoleDetail($roleId);
        return Response::success([
            "role" => new RoleDetailResource($result)
        ]);
    }

    public function createRole(CreateRoleRequest $request)
    {
        $roleData = $request->all(['name', 'description']);
        $this->userRoleService->createRole($roleData);
        return Response::success();
    }

    public function updateRole(UpdateRoleRequest $request, int $roleId)
    {
        $roleData = $request->only(['name', 'description', 'permissions']);
        $this->userRoleService->updateRole($roleData, $roleId);
        return Response::success();
    }

    public function deleteRole(int $roleId)
    {
        $this->userRoleService->deleteRole($roleId);
        return Response::success();
    }
}
