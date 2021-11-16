<?php

namespace App\Services\WebService;

use App\Constants\UserRoleDefault;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Models\UserRoleModel;
use App\Repositories\Criteria\SearchRolesCriteria;
use App\Repositories\Repository;
use App\Services\AbstractService;

class UserRoleService extends AbstractService
{
    protected $userRoleRepository;

    public function __construct(UserRoleModel $userRoleModel)
    {
        $this->userRoleRepository = new Repository($userRoleModel);
    }

    public function retrieveListRoles(array $queryParam, int $limit)
    {
        try {
            $currentUser = app('currentUser');
            return $this->userRoleRepository
                ->pushCriteria(new SearchRolesCriteria($queryParam))
                ->where('company_id', '=', $currentUser->company_id)
                ->orderBy('created_at', 'desc')
                ->paginate($limit);
        } catch (\Exception $e) {
            report($e);
            throw new SystemException('messages.system_error');
        }
    }

    public function retrieveRoleDetail(int $roleId)
    {
        $currentUser = app('currentUser');
        $roleEntity = $this->userRoleRepository
            ->where('company_id', '=', $currentUser->company_id)
            ->find($roleId);
        if (!$roleEntity) {
            throw new NotFoundException('messages.role_does_not_exist');
        }
        return $roleEntity;
    }

    public function createRole(array $data)
    {
        try {
            $this->beginTransaction();
            $currentUser = app('currentUser');
            $data['company_id'] = $currentUser->company_id;
            $data['permissions'] = json_encode(UserRoleDefault::BLANK);
            $this->userRoleRepository->create($data);
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('errors.system_error');
        }
    }

    public function updateRole(array $data, int $roleId)
    {
        $currentUser = app('currentUser');
        $roleEntity = $this->userRoleRepository
            ->where('company_id', '=', $currentUser->company_id)
            ->find($roleId);
        if (!$roleEntity) {
            throw new NotFoundException('messages.role_does_not_exist');
        }
        $dataUpdate = [];
        if (array_key_exists('name', $data) && $data['name']) {
            $dataUpdate['name'] = $data['name'];
        }
        if (array_key_exists('description', $data)) {
            $dataUpdate['description'] = $data['description'];
        }
        if (array_key_exists('permissions', $data) && $data['permissions']) {
            if ($roleEntity->is_admin) {
                throw new ForbiddenException('messages.can_not_update_permission_admin');
            }
            $dataUpdate['permissions'] = $data['permissions'];
        }
        $roleEntity->update($dataUpdate);
    }

    public function deleteRole(int $roleId)
    {
        $currentUser = app('currentUser');
        $roleEntity = $this->userRoleRepository
            ->where('company_id', '=', $currentUser->company_id)
            ->find($roleId);
        if (!$roleEntity) {
            throw new NotFoundException('user.not_found');
        }
        if ($roleEntity->is_default) {
            throw new ForbiddenException('messages.can_not_delete_permission_default');
        }
        if ($roleEntity->users->count() > 0) {
            throw new ForbiddenException('messages.can_not_delete_permission_assigned_user');
        }
        try {
            $this->beginTransaction();
            $roleEntity->delete();
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('errors.system_error');
        }
    }
}
