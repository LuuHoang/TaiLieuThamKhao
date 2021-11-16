<?php

namespace App\Http\Controllers\AppControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserDetailResourceV2;
use App\Http\Resources\VerifyUserForAppResources;
use App\Http\Resources\VerifyUserForAppResourcesV2;
use App\Services\AppService\UserService;
use App\Supports\Facades\Response\Response;

class UserController extends Controller
{
    private $_userService;

    public function __construct(UserService $userService)
    {
        $this->_userService = $userService;
    }

    public function getCurrentUser()
    {
        $currentUserId = app('currentUser')->id;
        $user = $this->_userService->getUser($currentUserId);
        return Response::success([
            'user_data' =>  new UserDetailResource($user)
        ]);
    }

    public function verifyUser()
    {
        $currentUser = app('currentUser');
        return Response::success([
            'user_data' =>  new VerifyUserForAppResources($currentUser)
        ]);
    }


    public function getCurrentUserV2()
    {
        $currentUserId = app('currentUser')->id;
        $user = $this->_userService->getUser($currentUserId);
        return Response::success([
            'user_data' =>  new UserDetailResourceV2($user)
        ]);
    }

    public function verifyUserV2()
    {
        $currentUser = app('currentUser');
        return Response::success([
            'user_data' =>  new VerifyUserForAppResourcesV2($currentUser)
        ]);
    }
}
