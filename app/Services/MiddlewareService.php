<?php

namespace App\Services;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Models\AlbumModel;
use App\Models\UserModel;
use App\Repositories\Repository;

class MiddlewareService extends AbstractService
{
    private $_userRepository;
    private $_albumRepository;
    private $_commonService;

    public function __construct (
        UserModel $userModel,
        AlbumModel $albumModel,
        CommonService $commonService
    )
    {
        $this->_userRepository = new Repository($userModel);
        $this->_albumRepository = new Repository($albumModel);
        $this->_commonService = $commonService;
    }

    public function checkUserBelongToCompany(int $userId)
    {
        $companyId = app('currentUser')->company_id;
        $userEntity = $this->_userRepository->find($userId);

        if ($userEntity == null) {
            throw new NotFoundException('messages.user_does_not_exist');
        }
        if ($userEntity->company_id != $companyId) {
            throw new ForbiddenException('messages.not_have_permission');
        }
    }

    public function checkAlbumBelongToUser(int $albumId)
    {
        $currentUser = app('currentUser');
        $albumEntity = $this->_albumRepository->with(['user'])->find($albumId);

        if ($albumEntity == null) {
            throw new NotFoundException('messages.album_does_not_exist');
        }

        $userAlbum = $albumEntity->user;

        if ($currentUser->company_id != $userAlbum->company_id) {
            throw new ForbiddenException('messages.not_have_permission');
        }

        if ($this->_commonService->isViewPublicAlbums(json_decode($currentUser->userRole->permissions ?? '[]', true))
            || ($this->_commonService->checkViewProtectedAlbums($userAlbum) && $userAlbum->user_created_id == $currentUser->id)) {
            return true;
        }

        if ($currentUser->id != $userAlbum->id) {
            throw new ForbiddenException('messages.not_have_permission');
        }
    }
}
