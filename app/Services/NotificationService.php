<?php

namespace App\Services;

use App\Constants\Boolean;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Models\NotificationModel;
use App\Repositories\Repository;

class NotificationService extends AbstractService
{
    protected $_notificationRepository;

    public function __construct(NotificationModel $notificationModel)
    {
        $this->_notificationRepository = new Repository($notificationModel);
    }

    public function getListNotification(int $limit, Array $paramQuery)
    {
        try {
            $currentUser = app('currentUser');
            $queryData = $this->_notificationRepository
                ->where('user_id', '=', $currentUser->id);
            if (!empty($paramQuery['search'])) {
                $queryData = $queryData
                    ->where('title', 'like', "%" . $paramQuery['search'] . "%");
            }
            return $queryData->orderBy('created_time', 'DESC')->paginate($limit);
        } catch (\Exception $e) {
            report($e);
            throw new SystemException('messages.system_error');
        }
    }

    public function getNumberNotificationUnread()
    {
        $currentUser = app('currentUser');
        $notificationEntities = $this->_notificationRepository
            ->where('user_id', '=', $currentUser->id)->all();
        return [
            "unread"    => $notificationEntities->where('status', '=', Boolean::FALSE)->count(),
            "total"     => $notificationEntities->count()
        ];
    }

    public function updateStatusNotification(int $notificationId)
    {
        $currentUser = app('currentUser');
        $notificationEntity = $this->_notificationRepository
            ->where('user_id', '=', $currentUser->id)
            ->find($notificationId);
        if ($notificationEntity == null) {
            throw new NotFoundException('messages.notification_does_not_exist');
        }
        $notificationEntity->update(['status' => Boolean::TRUE]);
    }

    public function deleteNotification(int $notificationId)
    {
        $currentUser = app('currentUser');
        $notificationEntity = $this->_notificationRepository
            ->where('user_id', '=', $currentUser->id)
            ->find($notificationId);
        if ($notificationEntity == null) {
            throw new NotFoundException('messages.notification_does_not_exist');
        }
        $notificationEntity->delete();
    }

    public function updateStatusAllNotifications()
    {
        $currentUser = app('currentUser');
        $this->_notificationRepository
            ->update([
                ['user_id', '=', $currentUser->id],
                ['status', '=', Boolean::FALSE]
            ], ['status' => Boolean::TRUE]);
    }
}
