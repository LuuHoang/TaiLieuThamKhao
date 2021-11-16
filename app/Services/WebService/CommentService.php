<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-04
 * Time: 14:15
 */

namespace App\Services\WebService;

use App\Constants\Boolean;
use App\Constants\CommentAction;
use App\Constants\CommentCreator;
use App\Constants\Media;
use App\Constants\NotificationType;
use App\Constants\ServerCommunication;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnprocessableException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class CommentService extends \App\Services\CommentService
{
    public function getListShareAlbumLocationComment(Array $param, int $albumId, int $locationId, int $limit)
    {
        $token = Arr::pull($param, 'token');
        $password = Arr::pull($param, 'password');
        $sharedAlbumEntity = $this->retrieveSharedAlbum($token, $password);

        $locationEntity = $sharedAlbumEntity->album->albumLocations()->find($locationId);
        if ($locationEntity == null) {
            throw new NotFoundException('messages.album_location_does_not_exist');
        }

        if ($locationEntity->album_id != $albumId) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        if (!$this->commonService->showComment($sharedAlbumEntity->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }

//        $commentIds = [];
//        $allComment = $locationEntity->comments()->get();
//
//        foreach ($allComment as $comment) {
//            if ($comment->creator_type === CommentCreator::USER) {
//                if ($this->commonService->checkSettingCommentPublic($comment->user) || $locationEntity->album->user_id === $comment->creator_id) {
//                    $commentIds[] = $comment->id;
//                }
//            } else {
//                if ($comment->shareUser->email === $sharedAlbumEntity->email) {
//                    $commentIds[] = $comment->id;
//                }
//            }
//        }

        return $locationEntity->comments()->paginate($limit);
    }

    public function createShareAlbumLocationComment(Array $param, int $albumId, int $locationId)
    {
        $token = Arr::pull($param, 'token');
        $password = Arr::pull($param, 'password');
        $sharedAlbumEntity = $this->retrieveSharedAlbum($token, $password);

        $locationEntity = $sharedAlbumEntity->album->albumLocations()->find($locationId);
        if ($locationEntity == null) {
            throw new NotFoundException('messages.album_location_does_not_exist');
        }

        if ($locationEntity->album_id != $albumId) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        if (!$this->commonService->showComment($sharedAlbumEntity->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }

        $param['creator_id'] = $sharedAlbumEntity->id;
        $commentEntity =  $locationEntity->comments()->create($param);
        $albumEntity = $sharedAlbumEntity->album;

        $dataPayload        = $this->generateDataPayload(CommentAction::CREATED, $albumEntity, $locationId, $commentEntity);
        $notificationUsers  = $this->getTargetUserNotificationOfAlbumLocation($locationEntity);
        $notificationTitle  = $sharedAlbumEntity->full_name . "さんが";
        if ($albumEntity->highlightInformation != null && !empty($albumEntity->highlightInformation->value)) {
            $notificationTitle  = $notificationTitle . "「" . $albumEntity->highlightInformation->value . "」";
        }
        $notificationTitle  = $notificationTitle . "アルバムの" . "「" . $locationEntity->title . "」" . "位置についてコメントしました。";
        $notificationBody   = $commentEntity->content ?? "";

        $notifications = [];
        foreach ($notificationUsers as $key => $notificationUser) {
            $notification = $this->notificationRepository->create([
                "user_id"       =>  $key,
                "title"         =>  $notificationTitle,
                "type"          =>  NotificationType::LOCATION_COMMENT,
                "data"          =>  json_encode($dataPayload),
                "created_time"  =>  time(),
                "status"        =>  Boolean::FALSE
            ]);
            $notifications[][$key] = $this->generateNotificationPayload($notification);
        }
        $dataPayload['notifications']   = $notifications;

        $dataRequest = [
            "users"                 =>  $this->getTargetUsersOfAlbum($albumEntity),
            "notification_users"    =>  $notificationUsers,
            "shared_users"          =>  $this->getTargetSharedUsersOfAlbumForShareUser($albumEntity),
            "notification"          =>  $this->generateNotificationData($notificationTitle, $notificationBody),
            "data"                  =>  $dataPayload
        ];
        $this->httpService->sendRequest('POST', env('SOCKET_URL') . ServerCommunication::LOCATION_COMMENT_EVENT, $dataRequest);
        return $commentEntity;
    }

    public function editShareAlbumLocationComment (Array $param, int $locationId, int $commentId)
    {
        $token = Arr::pull($param, 'token');
        $password = Arr::pull($param, 'password');
        $sharedAlbumEntity = $this->retrieveSharedAlbum($token, $password);

        $locationCommentEntity = $this->albumLocationCommentRepository
            ->with(['albumLocation.album'])
            ->where('album_location_id', '=', $locationId)
            ->where('creator_type', '=', $param['creator_type'])
            ->find($commentId);

        if ($locationCommentEntity == null) {
            throw new NotFoundException('messages.comment_does_not_exist');
        }

        if ($locationCommentEntity->shareUser->email != $sharedAlbumEntity->email) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        if (!$this->commonService->showComment($sharedAlbumEntity->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }

        $commentEntity = tap($locationCommentEntity)->update($param);

        $albumEntity = $locationCommentEntity->albumLocation->album;
        $dataRequest = [
            "users"                 =>  $this->getTargetUsersOfAlbum($albumEntity),
            "shared_users"          =>  $this->getTargetSharedUsersOfAlbumForShareUser($albumEntity),
            "data"                  =>  $this->generateDataPayload(CommentAction::UPDATED, $albumEntity, $locationId, $commentEntity)
        ];
        $this->httpService->sendRequest('POST', env('SOCKET_URL') . ServerCommunication::LOCATION_COMMENT_EVENT, $dataRequest);
        return $commentEntity;
    }

    public function getListShareAlbumLocationMediaComment(Array $param, int $locationId, int $mediaId, int $limit)
    {
        $token = Arr::pull($param, 'token');
        $password = Arr::pull($param, 'password');
        $sharedAlbumEntity = $this->retrieveSharedAlbum($token, $password);

        $locationEntity = $sharedAlbumEntity->album->albumLocations()->find($locationId);
        if ($locationEntity == null) {
            throw new NotFoundException('messages.album_location_does_not_exist');
        }

        $locationMediaEntity = $locationEntity->albumLocationMedias()->find($mediaId);
        if ($locationMediaEntity == null) {
            throw new NotFoundException('messages.media_does_not_exist');
        }

        if (!$this->commonService->showComment($sharedAlbumEntity->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }

//        $commentIds = [];
//        $allComment = $locationMediaEntity->comments()->get();
//
//        foreach ($allComment as $comment) {
//            if ($comment->creator_type === CommentCreator::USER) {
//                if ($this->commonService->checkSettingCommentPublic($comment->user) || $locationEntity->album->user_id === $comment->creator_id) {
//                    $commentIds[] = $comment->id;
//                }
//            } else {
//                if ($comment->shareUser->email === $sharedAlbumEntity->email) {
//                    $commentIds[] = $comment->id;
//                }
//            }
//        }

        return $locationMediaEntity->comments()->paginate($limit);
    }

    public function createShareAlbumLocationMediaComment(Array $param, int $locationId, int $mediaId)
    {
        $token = Arr::pull($param, 'token');
        $password = Arr::pull($param, 'password');
        $sharedAlbumEntity = $this->retrieveSharedAlbum($token, $password);

        $locationEntity = $sharedAlbumEntity->album->albumLocations()->find($locationId);
        if ($locationEntity == null) {
            throw new NotFoundException('messages.album_location_does_not_exist');
        }

        $locationMediaEntity = $locationEntity->albumLocationMedias()->find($mediaId);
        if ($locationMediaEntity == null) {
            throw new NotFoundException('messages.media_does_not_exist');
        }

        if (!$this->commonService->showComment($sharedAlbumEntity->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }

        $param['creator_id'] = $sharedAlbumEntity->id;
        $commentEntity = $locationMediaEntity->comments()->create($param);
        $albumEntity = $sharedAlbumEntity->album;

        $dataPayload        = $this->generateDataPayload(CommentAction::CREATED, $albumEntity, $locationId, $commentEntity, $mediaId);
        $notificationUsers  = $this->getTargetUserNotificationOfAlbumLocationMedia($locationMediaEntity);
        $notificationTitle  = $sharedAlbumEntity->full_name . "さんが";
        if ($albumEntity->highlightInformation != null && !empty($albumEntity->highlightInformation->value)) {
            $notificationTitle  = $notificationTitle . "「" . $albumEntity->highlightInformation->value . "」";
        }
        $notificationTitle  = $notificationTitle . "アルバムの" . "「" . $locationMediaEntity->albumLocation->title . "」位置の";
        if ($locationMediaEntity->type == Media::TYPE_VIDEO) {
            $notificationTitle  = $notificationTitle . "動画";
        } else {
            $notificationTitle  = $notificationTitle . "写真";
        }
        $notificationTitle  = $notificationTitle . "についてコメントしました。";
        $notificationBody   = $commentEntity->content ?? "";
        $notifications = [];

        foreach ($notificationUsers as $key => $notificationUser) {
            $notification = $this->notificationRepository->create([
                "user_id"       =>  $key,
                "title"         =>  $notificationTitle,
                "type"          =>  NotificationType::MEDIA_COMMENT,
                "data"          =>  json_encode($dataPayload),
                "created_time"  =>  time(),
                "status"        =>  Boolean::FALSE
            ]);
            $notifications[][$key] = $this->generateNotificationPayload($notification);
        }
        $dataPayload['notifications']   = $notifications;

        $dataRequest = [
            "users"                 =>  $this->getTargetUsersOfAlbum($albumEntity),
            "notification_users"    =>  $notificationUsers,
            "shared_users"          =>  $this->getTargetSharedUsersOfAlbumForShareUser($albumEntity),
            "notification"          =>  $this->generateNotificationData($notificationTitle, $notificationBody),
            "data"                  =>  $dataPayload
        ];
        $this->httpService->sendRequest('POST', env('SOCKET_URL') . ServerCommunication::MEDIA_COMMENT_EVENT, $dataRequest);
        return $commentEntity;
    }

    public function editShareAlbumLocationMediaComment(Array $param, int $mediaId, int $commentId)
    {
        $token = Arr::pull($param, 'token');
        $password = Arr::pull($param, 'password');
        $sharedAlbumEntity = $this->retrieveSharedAlbum($token, $password);

        $locationMediaCommentEntity = $this->albumLocationMediaCommentRepository
            ->with(['albumLocationMedia.albumLocation.album'])
            ->where('album_location_media_id', '=', $mediaId)
            ->where('creator_type', '=', $param['creator_type'])
            ->find($commentId);

        if ($locationMediaCommentEntity == null) {
            throw new NotFoundException('messages.comment_does_not_exist');
        }

        if ($locationMediaCommentEntity->shareUser->email != $sharedAlbumEntity->email) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        if (!$this->commonService->showComment($sharedAlbumEntity->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }

        $commentEntity = tap($locationMediaCommentEntity)->update($param);

        $locationEntity = $locationMediaCommentEntity->albumLocationMedia->albumLocation;
        $albumEntity = $locationEntity->album;

        $dataRequest = [
            "users"                 =>  $this->getTargetUsersOfAlbum($albumEntity),
            "shared_users"          =>  $this->getTargetSharedUsersOfAlbumForShareUser($albumEntity),
            "data"                  =>  $this->generateDataPayload(CommentAction::UPDATED, $albumEntity, $locationEntity->id, $commentEntity, $mediaId)
        ];
        $this->httpService->sendRequest('POST', env('SOCKET_URL') . ServerCommunication::MEDIA_COMMENT_EVENT, $dataRequest);
        return $commentEntity;
    }

    public function retrieveSharedAlbum(string $token, string $password)
    {
        $sharedAlbumEntity = $this->sharedAlbumRepository->with(['album'])->where('token', '=', $token)->first();

        if ($sharedAlbumEntity == null) {
            throw new UnprocessableException('messages.session_has_expired');
        }

        if ($sharedAlbumEntity->status == Boolean::FALSE) {
            throw new ForbiddenException('messages.album_sharing_link_has_expired');
        }

        if (!Hash::check($password, $sharedAlbumEntity->password)) {
            throw new ForbiddenException('messages.password_is_incorrect');
        }

        return $sharedAlbumEntity;
    }
}
