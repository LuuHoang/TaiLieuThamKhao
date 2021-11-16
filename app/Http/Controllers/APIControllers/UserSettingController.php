<?php

namespace App\Http\Controllers\APIControllers;

use App\Exceptions\SystemException;
use App\Http\Controllers\Controller;
use App\Services\APIServices\UserSettingService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\UserSettingResource;

class UserSettingController extends Controller
{

    private $_userSettingService;

    public function __construct(UserSettingService $userSettingService)
    {
        $this->_userSettingService = $userSettingService;
    }

    public function updateUserSetting(UpdateSettingRequest $request): JsonResponse
    {
        $userId = app('currentUser')->id;
        $result = $this->_userSettingService->updateSetting($request, $userId);

        if (!$result) {
            throw new SystemException('messages.system_error');
        }

        return Response::success([
            'user_setting' => new UserSettingResource($result)
        ]);
    }

    public function getUserSetting(): JsonResponse
    {
        $userId = app('currentUser')->id;
        $result = $this->_userSettingService->getUserSetting($userId);

        return Response::success([
            'user_setting' => $result
        ]);
    }
}
