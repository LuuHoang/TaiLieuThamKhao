<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIRequests\ChangePasswordRequest;
use App\Http\Requests\APIRequests\ForgotPasswordRequest;
use App\Http\Requests\APIRequests\ResetPasswordRequest;
use App\Http\Requests\APIRequests\UpdateDeviceTokenRequest;
use App\Services\AdminService;
use App\Services\APIServices\UserService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $_userService;
    private $_adminService;

    public function __construct(UserService $userService, AdminService $adminService)
    {
        $this->_userService = $userService;
        $this->_adminService = $adminService;
    }

    public function logout(Request $request)
    {
        $bearerToken = $request->bearerToken();
        $this->_userService->logout($bearerToken);
        return Response::success();
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $dataUser = $request->only('company_code', 'email');
        $this->_userService->sendForgetPasswordEmail($dataUser);
        return Response::success();
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->only('otp_code', 'password');
        $this->_userService->resetPassword($data);
        return Response::success();
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $bearerToken = $request->bearerToken();
        $currentUser = app('currentUser');
        $data = $request->only('old_password', 'password');
        $this->_userService->changePassword($currentUser, $data, $bearerToken);
        return Response::success();
    }

    public function updateDeviceToken(UpdateDeviceTokenRequest $request)
    {
        $bearerToken = $request->bearerToken();
        $params = $request->only(['device_token', 'os']);
        $this->_userService->updateDeviceToken($bearerToken, $params);
        return Response::success();
    }
}
