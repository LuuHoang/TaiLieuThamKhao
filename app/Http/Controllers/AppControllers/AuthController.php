<?php

namespace App\Http\Controllers\AppControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppRequests\LoginRequest;
use App\Http\Resources\UserLoginForAppResource;
use App\Services\AppService\UserService;
use App\Supports\Facades\Response\Response;

class AuthController extends Controller
{
    private $_userService;

    public function __construct(UserService $userService)
    {
        $this->_userService = $userService;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('company_code', 'email', 'password', 'os', 'device_token');
        $userLogin = $this->_userService->login($credentials);

        $userLoginResource = new UserLoginForAppResource($userLogin['user']);

        return Response::success([
            'user_data' => $userLoginResource,
            'user_token' => $userLogin['token']
        ]);
    }
}
