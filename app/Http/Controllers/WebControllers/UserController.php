<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\UpdateCurrentUserRequest;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserDetailResourceV2;
use App\Http\Resources\WebResources\VerifyUserForWebResources;
use App\Services\WebService\UserService;
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

    public function updateCurrentUser(UpdateCurrentUserRequest $request)
    {
        $currentUser = app('currentUser');
        $userData = $request->only('staff_code', 'full_name', 'address', 'email', 'department', 'position', 'avatar_path');
        $user = $this->_userService->updateUser($userData, $currentUser->id);
        return Response::success([
            'user_data' =>  new UserDetailResource($user)
        ]);
    }

    public function verifyUser()
    {
        $currentUser = app('currentUser');
        return Response::success([
            'user_data' => new VerifyUserForWebResources($currentUser),
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
}
