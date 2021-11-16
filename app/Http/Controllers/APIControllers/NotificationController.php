<?php

namespace App\Http\Controllers\APIControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Services\NotificationService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private $_notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->_notificationService = $notificationService;
    }

    public function getListNotification(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search']);

        $result = $this->_notificationService->getListNotification($limit, $paramQuery);

        return Response::pagination(
            NotificationResource::collection($result),
            $result->total(),
            $result->currentPage(),
            $limit
        );
    }

    public function getNumberNotificationUnread(Request $request)
    {
        $result = $this->_notificationService->getNumberNotificationUnread();
        return Response::success($result);
    }

    public function updateStatusNotification(int $notificationId)
    {
        $this->_notificationService->updateStatusNotification($notificationId);
        return Response::success();
    }

    public function deleteNotification(int $notificationId)
    {
        $this->_notificationService->deleteNotification($notificationId);
        return Response::success();
    }

    public function updateStatusAllNotifications()
    {
        $this->_notificationService->updateStatusAllNotifications();
        return Response::success();
    }
}
