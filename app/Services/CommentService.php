<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-04
 * Time: 14:15
 */

namespace App\Services;

use App\Constants\Boolean;
use App\Constants\CommentAction;
use App\Constants\CommentCreator;
use App\Constants\Media;
use App\Constants\NotificationType;
use App\Constants\ServerCommunication;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Models\AlbumLocationCommentModel;
use App\Models\AlbumLocationMediaCommentModel;
use App\Models\AlbumLocationMediaModel;
use App\Models\AlbumLocationModel;
use App\Models\AlbumModel;
use App\Models\NotificationModel;
use App\Models\SharedAlbumModel;
use App\Models\UserModel;
use App\Models\UserTokenModel;
use App\Repositories\Criteria\FilterBetweenDayCriteria;
use App\Repositories\Repository;
use Illuminate\Support\Collection;

class CommentService extends AbstractService
{
    protected $albumLocationCommentRepository;

    protected $albumLocationMediaCommentRepository;

    protected $albumLocationRepository;

    protected $albumLocationMediaRepository;

    protected $albumRepository;

    protected $sharedAlbumRepository;

    protected $httpService;

    protected $userTokenRepository;

    protected $notificationRepository;

    protected $commonService;

    public function __construct(
        AlbumLocationCommentModel $albumLocationCommentModel,
        AlbumLocationMediaCommentModel $albumLocationMediaCommentModel,
        AlbumLocationModel $albumLocationModel,
        AlbumLocationMediaModel $albumLocationMediaModel,
        AlbumModel $albumModel,
        SharedAlbumModel $sharedAlbumModel,
        HTTPService $httpService,
        UserTokenModel $userTokenModel,
        NotificationModel $notificationModel,
        CommonService $commonService
    )
    {
        $this->albumLocationCommentRepository = new Repository($albumLocationCommentModel);
        $this->albumLocationMediaCommentRepository = new Repository($albumLocationMediaCommentModel);
        $this->albumLocationRepository = new Repository($albumLocationModel);
        $this->albumLocationMediaRepository = new Repository($albumLocationMediaModel);
        $this->albumRepository = new Repository($albumModel);
        $this->sharedAlbumRepository = new Repository($sharedAlbumModel);
        $this->httpService = $httpService;
        $this->userTokenRepository = new Repository($userTokenModel);
        $this->notificationRepository = new Repository($notificationModel);
        $this->commonService = $commonService;
    }

    /**
     * @param int $albumId
     * @param int $locationId
     * @param int $limit
     * @param array $params
     * @return AlbumLocationCommentModel
     * @throws NotFoundException
     */
    public function getListLocationComment(int $albumId, int $locationId, int $limit, Array $params = [])
    {
        $albumLocationEntity = $this->albumLocationRepository
            ->where('album_id', '=',$albumId)
            ->find($locationId);

        if ($albumLocationEntity == null) {
            throw new NotFoundException('messages.album_location_does_not_exist');
        }
//        $currentUser = app('currentUser');
//        $commentIds = [];
//        $allComment = $albumLocationEntity->comments()->get();
//        if ($this->commonService->isAdmin($currentUser) || $currentUser->id === $albumLocationEntity->album->user_id || $currentUser->id === $albumLocationEntity->album->user->user_created_id) {
//            $commentIds = $allComment->pluck('id')->values()->toArray();
//        } else {
//            foreach ($allComment as $comment) {
//                if ($comment->creator_type === CommentCreator::USER
//                    && ($this->commonService->checkSettingCommentPublic($comment->user)
//                        || $albumLocationEntity->album->user_id === $comment->creator_id
//                        || $currentUser->id === $comment->creator_id
//                        || (!$this->commonService->isSubUser(json_decode($currentUser->userRole->permissions ?? '[]', true))
//                            && $this->commonService->isSubUser(json_decode($comment->user->userRole->permissions ?? '[]', true))
//                            && in_array($comment->creator_id, $currentUser->subUsers->pluck('id')->values()->toArray())))) {
//                    $commentIds[] = $comment->id;
//                }
//            }
//        }
        if (!$this->commonService->showComment($albumLocationEntity->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }
        if (!empty($params['after_time'])) {
            return $albumLocationEntity->comments()->where('created_at', '>', $params['after_time'])->paginate($limit);
        } else {
            return $albumLocationEntity->comments()->paginate($limit);
        }
    }

    /**
     * @param int $albumId
     * @param int $locationId
     * @param array $params
     * @return AlbumLocationCommentModel
     * @throws NotFoundException
     */
    public function getListNewLocationComment(int $albumId, int $locationId, Array $params = [])
    {
        $albumLocationEntity = $this->albumLocationRepository
            ->where('album_id', '=',$albumId)
            ->find($locationId);

        if ($albumLocationEntity == null) {
            throw new NotFoundException('messages.album_location_does_not_exist');
        }
//        $currentUser = app('currentUser');
//        $commentIds = [];
//        $allComment = $albumLocationEntity->comments()->get();
//        if ($this->commonService->isAdmin($currentUser) || $currentUser->id === $albumLocationEntity->album->user_id || $currentUser->id === $albumLocationEntity->album->user->user_created_id) {
//            $commentIds = $allComment->pluck('id')->values()->toArray();
//        } else {
//            foreach ($allComment as $comment) {
//                if ($comment->creator_type === CommentCreator::USER
//                    && ($this->commonService->checkSettingCommentPublic($comment->user)
//                        || $albumLocationEntity->album->user_id === $comment->creator_id
//                        || $currentUser->id === $comment->creator_id
//                        || (!$this->commonService->isSubUser(json_decode($currentUser->userRole->permissions ?? '[]', true))
//                            && $this->commonService->isSubUser(json_decode($comment->user->userRole->permissions ?? '[]', true))
//                            && in_array($comment->creator_id, $currentUser->subUsers->pluck('id')->values()->toArray())))) {
//                    $commentIds[] = $comment->id;
//                }
//            }
//        }
        if (!$this->commonService->showComment($albumLocationEntity->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }
        if (!empty($params['after_time'])) {
            return $albumLocationEntity->comments()->where('created_at', '>', $params['after_time'])->get();
        } else {
            return $albumLocationEntity->comments()->get();
        }
    }

    /**
     * @param array $data
     * @param int $albumId
     * @param int $locationId
     * @return
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws \App\Exceptions\SystemException
     */
    public function addLocationComment(array $data, int $albumId, int $locationId)
    {
        $currentUser = app('currentUser');

        $albumLocationEntity = $this->albumLocationRepository
            ->with(['album'])
            ->where('album_id', '=',$albumId)
            ->find($locationId);

        if ($albumLocationEntity == null) {
            throw new NotFoundException('messages.album_location_does_not_exist');
        }

        if (!$this->commonService->showComment($albumLocationEntity->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }

        $data['creator_id'] = $currentUser->id;
        $commentEntity      = $albumLocationEntity->comments()->create($data);
        $albumEntity        = $albumLocationEntity->album;
        $dataPayload        = $this->generateDataPayload(CommentAction::CREATED, $albumEntity, $locationId, $commentEntity);
        $notificationUsers  = $this->getTargetUserNotificationOfAlbumLocation($albumLocationEntity, $currentUser);
        $notificationTitle  = $currentUser->full_name . "さんが";
        if ($albumEntity->highlightInformation != null && !empty($albumEntity->highlightInformation->value)) {
            $notificationTitle  = $notificationTitle . "「" . $albumEntity->highlightInformation->value . "」";
        }
        $notificationTitle  = $notificationTitle . "アルバムの" . "「" . $albumLocationEntity->title . "」" . "位置についてコメントしました。";
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
            "shared_users"          =>  $this->getTargetSharedUsersOfAlbum($albumEntity),
            "notification"          =>  $this->generateNotificationData($notificationTitle, $notificationBody),
            "data"                  =>  $dataPayload
        ];
        $this->httpService->sendRequest('POST', env('SOCKET_URL') . ServerCommunication::LOCATION_COMMENT_EVENT, $dataRequest);
        return $commentEntity;
    }

    /**
     * @param array $data
     * @param int $locationId
     * @param int $commentId
     * @return
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws \App\Exceptions\SystemException
     */
    public function editLocationComment(array $data, int $locationId, int $commentId)
    {
        $currentUser = app('currentUser');

        $locationCommentEntity = $this->albumLocationCommentRepository
            ->with(['albumLocation.album'])
            ->where('album_location_id', '=', $locationId)
            ->where('creator_type', '=', $data['creator_type'])
            ->find($commentId);
        if ($locationCommentEntity == null) {
            throw new NotFoundException('messages.comment_does_not_exist');
        }

        if ($currentUser->id != $locationCommentEntity->creator_id) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        if (!$this->commonService->showComment($locationCommentEntity->albumLocation->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }

        $commentEntity = tap($locationCommentEntity)->update($data);
        $albumEntity = $locationCommentEntity->albumLocation->album;
        $dataRequest = [
            "users"                 =>  $this->getTargetUsersOfAlbum($albumEntity),
            "shared_users"          =>  $this->getTargetSharedUsersOfAlbum($albumEntity),
            "data"                  =>  $this->generateDataPayload(CommentAction::UPDATED, $albumEntity, $locationId, $commentEntity)
        ];
        $this->httpService->sendRequest('POST', env('SOCKET_URL') . ServerCommunication::LOCATION_COMMENT_EVENT, $dataRequest);
        return $commentEntity;
    }

    /**
     * @param int $locationId
     * @param int $mediaId
     * @param int $limit
     * @param array $params
     * @return AlbumLocationMediaCommentModel
     * @throws NotFoundException
     */
    public function getListMediaComment(int $locationId, int $mediaId, int $limit, Array $params = [])
    {
//        $currentUser = app('currentUser');
        $locationMediaEntity = $this->albumLocationMediaRepository
            ->where('album_location_id', '=',$locationId)
            ->find($mediaId);
        if ($locationMediaEntity == null) {
            throw new NotFoundException('messages.media_does_not_exist');
        }
//        $commentIds = [];
//        $allComment = $locationMediaEntity->comments()->get();
//        if ($this->commonService->isAdmin($currentUser) || $currentUser->id === $locationMediaEntity->albumLocation->album->user_id || $currentUser->id === $locationMediaEntity->albumLocation->album->user->user_created_id) {
//            $commentIds = $allComment->pluck('id')->values()->toArray();
//        } else {
//            foreach ($allComment as $comment) {
//                if ($comment->creator_type === CommentCreator::USER
//                    && ($this->commonService->checkSettingCommentPublic($comment->user)
//                        || $locationMediaEntity->albumLocation->album->user_id === $comment->creator_id
//                        || $currentUser->id === $comment->creator_id
//                        || (!$this->commonService->isSubUser(json_decode($currentUser->userRole->permissions ?? '[]', true))
//                            && $this->commonService->isSubUser(json_decode($comment->user->userRole->permissions ?? '[]', true))
//                            && in_array($comment->creator_id, $currentUser->subUsers->pluck('id')->values()->toArray())))) {
//                    $commentIds[] = $comment->id;
//                }
//            }
//        }
        if (!$this->commonService->showComment($locationMediaEntity->albumLocation->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }
        if (!empty($params['after_time'])) {
            return $locationMediaEntity->comments()->where('created_at', '>', $params['after_time'])->paginate($limit);
        } else {
            return $locationMediaEntity->comments()->paginate($limit);
        }
    }

    /**
     * @param int $locationId
     * @param int $mediaId
     * @param array $params
     * @return AlbumLocationMediaCommentModel
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function getListNewMediaComment(int $locationId, int $mediaId, Array $params = [])
    {
//        $currentUser = app('currentUser');
        $locationMediaEntity = $this->albumLocationMediaRepository
            ->where('album_location_id', '=',$locationId)
            ->find($mediaId);
        if ($locationMediaEntity == null) {
            throw new NotFoundException('messages.media_does_not_exist');
        }
//        $commentIds = [];
//        $allComment = $locationMediaEntity->comments()->get();
//        if ($this->commonService->isAdmin($currentUser) || $currentUser->id === $locationMediaEntity->albumLocation->album->user_id || $currentUser->id === $locationMediaEntity->albumLocation->album->user->user_created_id) {
//            $commentIds = $allComment->pluck('id')->values()->toArray();
//        } else {
//            foreach ($allComment as $comment) {
//                if ($comment->creator_type === CommentCreator::USER
//                    && ($this->commonService->checkSettingCommentPublic($comment->user)
//                        || $locationMediaEntity->albumLocation->album->user_id === $comment->creator_id
//                        || $currentUser->id === $comment->creator_id
//                        || (!$this->commonService->isSubUser(json_decode($currentUser->userRole->permissions ?? '[]', true))
//                            && $this->commonService->isSubUser(json_decode($comment->user->userRole->permissions ?? '[]', true))
//                            && in_array($comment->creator_id, $currentUser->subUsers->pluck('id')->values()->toArray())))) {
//                    $commentIds[] = $comment->id;
//                }
//            }
//        }
        if (!$this->commonService->showComment($locationMediaEntity->albumLocation->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }
        if (!empty($params['after_time'])) {
            return $locationMediaEntity->comments()->where('created_at', '>', $params['after_time'])->get();
        } else {
            return $locationMediaEntity->comments()->get();
        }
    }

    /**
     * @param array $data
     * @param int $locationId
     * @param int $mediaId
     * @return
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws \App\Exceptions\SystemException
     */
    public function addMediaComment(array $data, int $locationId, int $mediaId)
    {
        $currentUser = app('currentUser');
        $locationMediaEntity = $this->albumLocationMediaRepository
            ->with(['albumLocation.album'])
            ->where('album_location_id', '=',$locationId)
            ->find($mediaId);
        if ($locationMediaEntity == null) {
            throw new NotFoundException('messages.media_does_not_exist');
        }

        if (!$this->commonService->showComment($locationMediaEntity->albumLocation->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }

        $data['creator_id'] = $currentUser->id;

        $commentEntity = $locationMediaEntity->comments()->create($data);

        $albumEntity = $locationMediaEntity->albumLocation->album;

        $dataPayload        = $this->generateDataPayload(CommentAction::CREATED, $albumEntity, $locationId, $commentEntity, $mediaId);
        $notificationUsers  = $this->getTargetUserNotificationOfAlbumLocationMedia($locationMediaEntity, $currentUser);
        $notificationTitle  = $currentUser->full_name . "さんが";
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
            "shared_users"          =>  $this->getTargetSharedUsersOfAlbum($albumEntity),
            "notification"          =>  $this->generateNotificationData($notificationTitle, $notificationBody),
            "data"                  =>  $dataPayload
        ];
        $this->httpService->sendRequest('POST', env('SOCKET_URL') . ServerCommunication::MEDIA_COMMENT_EVENT, $dataRequest);
        return $commentEntity;
    }

    /**
     * @param array $data
     * @param int $mediaId
     * @param int $commentId
     * @return AlbumLocationMediaCommentModel
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws \App\Exceptions\SystemException
     */
    public function editMediaComment(array $data, int $mediaId, int $commentId)
    {
        $currentUser = app('currentUser');
        $mediaCommentEntity = $this->albumLocationMediaCommentRepository
            ->with(['albumLocationMedia.albumLocation.album'])
            ->where('album_location_media_id', '=', $mediaId)
            ->where('creator_type', '=', $data['creator_type'])
            ->find($commentId);
        if ($mediaCommentEntity == null) {
            throw new NotFoundException('messages.comment_does_not_exist');
        }

        if ($currentUser->id != $mediaCommentEntity->creator_id) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        if (!$this->commonService->showComment($mediaCommentEntity->albumLocationMedia->albumLocation->album)) {
            throw new ForbiddenException('messages.comment_turned_off');
        }

        $commentEntity = tap($mediaCommentEntity)->update($data);
        $locationEntity = $mediaCommentEntity->albumLocationMedia->albumLocation;
        $albumEntity = $locationEntity->album;

        $dataRequest = [
            "users"                 =>  $this->getTargetUsersOfAlbum($albumEntity),
            "shared_users"          =>  $this->getTargetSharedUsersOfAlbum($albumEntity),
            "data"                  =>  $this->generateDataPayload(CommentAction::UPDATED, $albumEntity, $locationEntity->id, $commentEntity, $mediaId)
        ];
        $this->httpService->sendRequest('POST', env('SOCKET_URL') . ServerCommunication::MEDIA_COMMENT_EVENT, $dataRequest);
        return $commentEntity;
    }

    protected function getTargetUsersOfAlbum(AlbumModel $albumEntity) {
        $userEntity = $albumEntity->user;
        $allUser = $userEntity->company->users->all();
        $targetUser = [];
        foreach ($allUser as $user) {
            if ($this->commonService->isViewPublicAlbums(json_decode($user->userRole->permissions ?? '[]', true))) {
                $targetUser[] = $user->id;
            }
        }
        if (!in_array($userEntity->id, $targetUser)) {
            $targetUser[] = $userEntity->id;
        }
        if ($this->commonService->checkViewProtectedAlbums($userEntity)) {
            $userManager = $userEntity->userCreated;
            if (!in_array($userManager->id, $targetUser)) {
                $targetUser[] = $userManager->id;
            }
        }
        return $targetUser;
    }

    protected function getTargetUserNotificationOfAlbumLocation(AlbumLocationModel $albumLocationEntity, UserModel $currentUser = null) {
        $targetUsers = [];
        $targetUserIds = [];
        if ($currentUser == null || $currentUser->id != $albumLocationEntity->album->user->id) {
            $targetUserIds[] = $albumLocationEntity->album->user->id;
        }
        $locationCommentUserIds = $albumLocationEntity->comments
            ->where('creator_type', '=', CommentCreator::USER)
            ->where('creator_id', '!=', $currentUser->id ?? null)
            ->whereNotIn('creator_id', $targetUserIds)
            ->pluck('creator_id')
            ->all();
        if (!empty($locationCommentUserIds)) {
            foreach ($locationCommentUserIds as $locationCommentUserId) {
                if (!in_array($locationCommentUserId, $targetUserIds)) {
                    $targetUserIds[] = $locationCommentUserId;
                }
            }
        }
        if ($albumLocationEntity->albumLocationMedias->isNotEmpty()) {
            foreach ($albumLocationEntity->albumLocationMedias as $media) {
                $locationMediaCommentUserIds = $media->comments
                    ->where('creator_type', '=', CommentCreator::USER)
                    ->where('creator_id', '!=', $currentUser->id ?? null)
                    ->whereNotIn('creator_id', $targetUserIds)
                    ->pluck('creator_id')
                    ->all();
                if (!empty($locationMediaCommentUserIds)) {
                    foreach ($locationMediaCommentUserIds as $locationMediaCommentUserId) {
                        if (!in_array($locationMediaCommentUserId, $targetUserIds)) {
                            $targetUserIds[] = $locationMediaCommentUserId;
                        }
                    }
                }
            }
        }
        $userTokens = $this->userTokenRepository->whereIn('user_id', $targetUserIds)->all();
        if ($userTokens->isNotEmpty()) {
            foreach ($userTokens as $userToken) {
                $targetUsers[$userToken->user_id][] = [
                    "id"            => $userToken->user_id,
                    "token"         => $userToken->token,
                    "os"            => $userToken->os,
                    "device_token"  => $userToken->device_token
                ];
            }
        }
        return $targetUsers;
    }

    protected function getTargetUserNotificationOfAlbumLocationMedia(AlbumLocationMediaModel $albumLocationMediaEntity, UserModel $currentUser = null) {
        $targetUsers = [];
        $targetUserIds = [];
        if ($currentUser == null || $currentUser->id != $albumLocationMediaEntity->albumLocation->album->user->id) {
            $targetUserIds[] = $albumLocationMediaEntity->albumLocation->album->user->id;
        }
        $locationMediaCommentUserIds = $albumLocationMediaEntity->comments
            ->where('creator_type', '=', CommentCreator::USER)
            ->where('creator_id', '!=', $currentUser->id ?? null)
            ->whereNotIn('creator_id', $targetUserIds)
            ->pluck('creator_id')
            ->all();
        if (!empty($locationMediaCommentUserIds)) {
            foreach ($locationMediaCommentUserIds as $locationMediaCommentUserId) {
                if (!in_array($locationMediaCommentUserId, $targetUserIds)) {
                    $targetUserIds[] = $locationMediaCommentUserId;
                }
            }
        }
        $locationCommentUserIds = $albumLocationMediaEntity->albumLocation->comments
            ->where('creator_type', '=', CommentCreator::USER)
            ->where('creator_id', '!=', $currentUser->id ?? null)
            ->whereNotIn('creator_id', $targetUserIds)
            ->pluck('creator_id')
            ->all();
        if (!empty($locationCommentUserIds)) {
            foreach ($locationCommentUserIds as $locationCommentUserId) {
                if (!in_array($locationCommentUserId, $targetUserIds)) {
                    $targetUserIds[] = $locationCommentUserId;
                }
            }
        }
        $userTokens = $this->userTokenRepository->whereIn('user_id', $targetUserIds)->all();
        if ($userTokens->isNotEmpty()) {
            foreach ($userTokens as $userToken) {
                $targetUsers[$userToken->user_id][] = [
                    "id"            => $userToken->user_id,
                    "os"            => $userToken->os,
                    "device_token"  => $userToken->device_token
                ];
            }
        }
        return $targetUsers;
    }

    protected function getTargetSharedUsersOfAlbum(AlbumModel $albumEntity) {
        $targetSharedUser = new Collection([]);
        $currentUser = app('currentUser');
        if ($currentUser->id === $albumEntity->user_id || $this->commonService->checkSettingCommentPublic($currentUser)) {
            $sharedAlbumEntities = $albumEntity->sharedAlbumActives;
            if ($sharedAlbumEntities->isNotEmpty()) {
                $targetSharedUser = $sharedAlbumEntities->pluck('id')->all();
            }
        }
        return $targetSharedUser;
    }

    protected function getTargetSharedUsersOfAlbumForShareUser(AlbumModel $albumEntity) {
        $targetSharedUser = new Collection([]);
        $shareUserEmail = app('shareUserEmail');
        $sharedAlbumEntities = $albumEntity->sharedAlbumActives;
        if ($sharedAlbumEntities->isNotEmpty()) {
            $targetSharedUser = $sharedAlbumEntities->where('email', $shareUserEmail)->pluck('id')->all();
        }
        return $targetSharedUser;
    }

    protected function generateNotificationData (string $notificationTitle, ?string $notificationBody = "") {
        return [
            "title" => $notificationTitle,
            "body"  =>  $notificationBody
        ];
    }

    protected function generateDataPayload (int $actionType, AlbumModel $albumEntity, int $locationId, $commentEntity, int $mediaId = null) {
        $data = [
            "action_type"       =>  $actionType,
            "album_id"          =>  $albumEntity->id,
            "album_highlight"   =>  $albumEntity->highlightInformation->value ?? "",
            "location_id"       =>  $locationId
        ];
        if ($mediaId != null) {
            $data['media_id'] = $mediaId;
        }
        $creator = [];
        if ($commentEntity->creator_type == CommentCreator::USER) {
            $creator = [
                "id"                    =>      $commentEntity->user->id,
                "full_name"             =>      $commentEntity->user->full_name,
                "email"                 =>      $commentEntity->user->email,
                "avatar_url"            =>      $commentEntity->user->avatar_url,
            ];
        } else if ($commentEntity->creator_type == CommentCreator::SHARE_USER) {
            $creator = [
                "shared_album_id"       =>      $commentEntity->shareUser->id,
                "full_name"             =>      $commentEntity->shareUser->full_name,
                "email"                 =>      $commentEntity->shareUser->email
            ];
        }
        $data['comment'] = [
            "id"            => $commentEntity->id,
            "creator"       => $creator,
            "creator_type"  => $commentEntity->creator_type,
            "content"       => $commentEntity->content,
            "create_at"     => $commentEntity->created_at->toISOString(),
            "update_at"     => $commentEntity->updated_at->toISOString()
        ];
        return $data;
    }

    protected function generateNotificationPayload(NotificationModel $notificationModel)
    {
        return [
            "id"            =>  $notificationModel->id,
            "title"         =>  $notificationModel->title,
            "type"          =>  $notificationModel->type,
            "meta_data"     =>  json_decode($notificationModel->data),
            "created_time"  =>  $notificationModel->created_time,
            "status"        =>  $notificationModel->status
        ];
    }

    public function getTopLocationCommentsByBetweenDays(array $locationIds, array $paramQuery)
    {
        return $this->albumLocationCommentRepository
            ->pushCriteria(new FilterBetweenDayCriteria($paramQuery))
            ->whereIn('album_location_id', $locationIds)
            ->orderBy('created_at', 'DESC')->all()->take(5);
    }

    public function getTopMediaCommentsByBetweenDays(array $mediaIds, array $paramQuery)
    {
        return $this->albumLocationMediaCommentRepository
            ->pushCriteria(new FilterBetweenDayCriteria($paramQuery))
            ->whereIn('album_location_media_id', $mediaIds)
            ->orderBy('created_at', 'DESC')->all()->take(5);
    }
}
