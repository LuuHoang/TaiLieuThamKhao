<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\LoginAdminRequest;
use App\Http\Requests\WebRequests\LoginRequest;
use App\Http\Resources\AdminResource;
use App\Http\Resources\UserLoginResource;
use App\Http\Resources\WebResources\UserLoginForWebResource;
use App\Services\AuthService;
use App\Services\WebService\UserService;
use App\Supports\Facades\Response\Response;

class AuthController extends Controller
{
    private $_authService;
    private $_userService;

    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->_authService = $authService;
        $this->_userService = $userService;
    }

    public function loginAdmin(LoginAdminRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $adminLogin = $this->_authService->loginAdmin($credentials);

        return Response::success([
            'admin_data' => new AdminResource($adminLogin['admin']),
            'company_data' => $adminLogin['company_data'],
            'admin_token' => $adminLogin['token']
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('company_code', 'email', 'password', 'os', 'device_token');
        $userLogin = $this->_userService->login($credentials);

        $userLoginResource = new UserLoginForWebResource($userLogin['user']);

        return Response::success([
            'user_data' => $userLoginResource,
            'user_token' => $userLogin['token']
        ]);
    }
}
