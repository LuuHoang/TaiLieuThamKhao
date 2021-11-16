<?php

namespace App\Services;

use App\Constants\Boolean;
use App\Constants\Permission;
use App\Constants\Platform;
use App\Constants\UserRoleDefault;
use App\Models\AlbumModel;
use App\Models\UserModel;

class CommonService extends AbstractService
{
    public function retrieveWebPermissions(array $permissions)
    {
        if (!empty($permissions) && $permissions[Platform::WEB][Permission::LOGIN]) {
            return $permissions[Platform::WEB]['module'];
        }
        return UserRoleDefault::BLANK[Platform::WEB]['module'];
    }

    public function retrieveAppPermissions(array $permissions)
    {
        if (!empty($permissions) && $permissions[Platform::APP][Permission::LOGIN]) {
            return $permissions[Platform::APP]['module'];
        }
        return UserRoleDefault::BLANK[Platform::APP]['module'];
    }

    public function checkPermission(array $permissions, string $permissionCheck, string $platform = Platform::WEB)
    {
        if (!empty($permissions) && $permissions[$platform][Permission::LOGIN]) {
            if ($permissionCheck === Permission::LOGIN) {
                return true;
            }
            if ((array_key_exists($permissionCheck, $permissions[$platform]['module']) && $permissions[$platform]['module'][$permissionCheck]) ||
                (array_key_exists($permissionCheck, $permissions['common']) && $permissions['common'][$permissionCheck])) {
                return true;
            }
        }
        if (!empty($permissions) && $permissionCheck === Permission::SUB_USER && $permissions['common'][$permissionCheck]) {
            return true;
        }
        return false;
    }

    public function isViewPublicAlbums(array $permissions)
    {
        if (!empty($permissions) && array_key_exists(Permission::ALBUM_ALL_USER_MANAGER, $permissions['common']) && $permissions['common'][Permission::ALBUM_ALL_USER_MANAGER]) {
            return true;
        }
        return false;
    }

    public function isViewProtectedAlbums(array $permissions)
    {
        if (!empty($permissions) && array_key_exists(Permission::ALBUM_SUB_USER_MANAGER, $permissions['common']) && $permissions['common'][Permission::ALBUM_SUB_USER_MANAGER]) {
            return true;
        }
        return false;
    }

    public function isSubUser(array $permissions)
    {
        if (!empty($permissions) && array_key_exists(Permission::SUB_USER, $permissions['common']) && $permissions['common'][Permission::SUB_USER]) {
            return true;
        }
        return false;
    }

    public function checkViewProtectedAlbums(UserModel $userModel)
    {
        if ($this->isSubUser(json_decode($userModel->userRole->permissions ?? '[]', true))
        && $this->isViewProtectedAlbums(json_decode($userModel->userCreated->userRole->permissions ?? '[]', true))) {
            return true;
        }
        return false;
    }

    public function isAdmin(UserModel $userModel)
    {
        if ($userModel->userRole->is_admin) {
            return true;
        }
        return false;
    }

    public function checkSettingCommentPublic(UserModel $userModel)
    {
        if ($userModel->userSetting->comment_display === Boolean::TRUE) {
            return true;
        }
        return false;
    }

    public function showComment(?AlbumModel $albumModel = null)
    {
        return $albumModel ? $albumModel->show_comment : Boolean::FALSE;
    }

    public function allowUpdateAlbum(UserModel $userEntity, AlbumModel $albumEntity)
    {
        $userCreateAlbum = $albumEntity->user;
            if ($userEntity->company_id === $userCreateAlbum->company_id
                && ($userEntity->id === $userCreateAlbum->id
                    || $this->isAdmin($userEntity)
                    || ($this->isSubUser(json_decode($userCreateAlbum->userRole->permissions ?? '[]', true))
                        && !$this->isSubUser(json_decode($userEntity->userRole->permissions ?? '[]', true))
                        && $userCreateAlbum->user_created_id === $userEntity->id))) {
                return true;
            }
        return false;
    }
}
