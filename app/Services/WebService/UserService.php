<?php

namespace App\Services\WebService;

use App\Constants\ContractStatus;
use App\Constants\Disk;
use App\Constants\Permission;
use App\Constants\Platform;
use App\Exceptions\AuthenticationDenied;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UnprocessableException;
use App\Models\CompanyModel;
use App\Models\UserModel;
use App\Repositories\Criteria\AdminCompanySearchUserCriteria;
use App\Repositories\Criteria\SearchRolesCriteria;
use App\Repositories\Criteria\SearchUsersCriteria;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserService extends \App\Services\UserService
{
    public function login(array $credentials)
    {
        $companyEntity = $this->_companyRepository
            ->where('company_code', '=', $credentials['company_code'])
            ->first();

        if ($companyEntity == null)
            throw new NotFoundException('messages.company_code_is_incorrect');

        $userEntity = $this->_userRepository
            ->where('company_id', '=', $companyEntity->id)
            ->where('email', '=', $credentials['email'])
            ->first();

        if ($userEntity == null || !Hash::check($credentials['password'], $userEntity->password)) {
            throw new UnauthorizedException('messages.email_or_password_is_incorrect');
        }

        if (!$this->_commonService->checkPermission(json_decode($userEntity->userRole->permissions ?? '[]', true), Permission::LOGIN, Platform::WEB)) {
            throw new AuthenticationDenied('messages.can_not_login_to_this_platform');
        }

        if (!empty($credentials['device_token'])) {
            $this->_userTokenRepository->delete([['device_token', '=', $credentials['device_token']]]);
        }
        $contract = $this->_contractService->retrieveContractInformationToLogin($userEntity->company_id);
        $arrayException = [ContractStatus::ARE_CONFIRMING,ContractStatus::EXPIRED,ContractStatus::CREATING,ContractStatus::UNFINISHED_PAYMENT,ContractStatus::EXPIRED_TRIAL];
        $arrayExpire    = [ContractStatus::HAS_MADE_A_DEPOSIT,ContractStatus::TRIAL];
        $arrayMessage   = [
            ContractStatus::ARE_CONFIRMING     => 'messages.user_has_no_effect',
            ContractStatus::HAS_MADE_A_DEPOSIT => 'messages.the_contract_has_been_signed_but_not_paid_for_completely',
            ContractStatus::TRIAL              => 'messages.the_trial_contract_has_expired',
            ContractStatus::EXPIRED            => 'messages.the_contract_has_expired',
            ContractStatus::CREATING           => 'messages.the_contract_creating',
            ContractStatus::UNFINISHED_PAYMENT => 'messages.the_contract_unfinished_payment',
            ContractStatus::EXPIRED_TRIAL      => 'messages.the_contract_expired_trial'
        ];
        if(in_array($contract->contract_status, $arrayException) ){
            throw new UnauthorizedException($arrayMessage[$contract->contract_status]);
        }
        if($contract->payment_term_all === 0 && in_array($contract->contract_status, $arrayExpire)){
            throw new UnauthorizedException($arrayMessage[$contract->contract_status]);
        }
        $token = Hash::make($userEntity->id . now()->timestamp);
        $userEntity->userTokens()->create([
            'token'         => $token,
            'os'            => $credentials['os'] ?? null,
            'device_token'  => $credentials['device_token'] ?? null
        ]);

        return [
            'token' => $token,
            'user' => $userEntity
        ];
    }

    public function updateUser(Array $userData, int $userId)
    {
        if (empty($userData)) {
            throw new UnprocessableException('messages.nothing_to_update');
        }
        try {
            $arrayDataUpdate = [];
            $userEntity = $this->_userRepository->find($userId);
            if (array_key_exists('staff_code',$userData)) {
                $arrayDataUpdate['staff_code'] = $userData['staff_code'];
            }
            if (array_key_exists('full_name',$userData)) {
                $arrayDataUpdate['full_name'] = $userData['full_name'];
            }
            if (array_key_exists('address',$userData)) {
                $arrayDataUpdate['address'] = $userData['address'];
            }
            if (array_key_exists('email',$userData)) {
                $arrayDataUpdate['email'] = $userData['email'];
            }
            if (array_key_exists('department',$userData)) {
                $arrayDataUpdate['department'] = $userData['department'];
            }
            if (array_key_exists('position',$userData)) {
                $arrayDataUpdate['position'] = $userData['position'];
            }
            if (array_key_exists('avatar_path',$userData)) {
                $arrayDataUpdate['avatar_path'] = $userData['avatar_path'];
                if ($userEntity->avatar_path) {
                    $this->_uploadMediaService->deleteMedia($userEntity->avatar_path, Disk::USER);
                }
            }
            $userEntity->update($arrayDataUpdate);
            return $userEntity;
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function createUser(Array $userData, int $companyId)
    {
        $company = $this->_companyRepository->with(['companyUsage', 'servicePackage'])->find($companyId);
        if ($company == null) {
            throw new NotFoundException('messages.company_id_is_incorrect');
        }
        $roleEntity = $this->_userRoleRepository->where('company_id', '=', $companyId)->find($userData['role_id']);
        if ($roleEntity == null) {
            throw new NotFoundException('messages.user_role_is_incorrect');
        }
        $isSubUser = $this->_commonService->checkPermission(json_decode($roleEntity->permissions, true), Permission::SUB_USER);
        if (!$isSubUser) {
            $userData['user_created_id'] = null;
            if ($company->companyUsage->count_user >= $company->servicePackage->max_user) {
                throw new ForbiddenException('messages.number_of_users_has_exceeded_limit');
            }
        }
        if ($isSubUser) {
            if (!array_key_exists('user_created_id',$userData) || !$userData['user_created_id']) {
                throw new UnprocessableException('messages.select_management_user');
            }
            $userCreate = $company->users()->find($userData['user_created_id']);
            if (!$userCreate || !$userCreate->userRole || $this->_commonService->isSubUser(json_decode($userCreate->userRole->permissions ?? '[]', true))) {
                throw new UnprocessableException('messages.management_user_is_incorrect');
            }
        }
        try {
            $this->beginTransaction();
            $userData['password'] = Hash::make($userData['password']);
            $newUser = $company->users()->create($userData);
            if (!$isSubUser) {
                $this->_dataUsageStatisticService->updateCompanyUsage($company->companyUsage, [
                    'count_user' => ($company->companyUsage->count_user + 1)
                ]);
            }
            $this->commitTransaction();
            return $newUser;
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function deleteUser(int $userId)
    {
        $user = $this->_userRepository->with(['company.companyUsage', 'userUsage'])->find($userId);
        if ($user == null) {
            throw new NotFoundException('messages.user_id_is_incorrect');
        }
        try {
            $this->beginTransaction();
            $companyUsage = $user->company->companyUsage;
            $userUsage =  $user->userUsage;
            $countUsers = $companyUsage->count_user;
            $countExtendData = $companyUsage->count_extend_data;
            if (!$user->userRole || !$this->_commonService->checkPermission(json_decode($user->userRole->permissions, true), Permission::SUB_USER)) {
                $countUsers = $countUsers - 1;
                $countExtendData = $countExtendData - $userUsage->extend_data;
            }
            $user->delete();
            $this->_dataUsageStatisticService->updateCompanyUsage($companyUsage, [
                'count_user' => $countUsers,
                'count_data' => ($companyUsage->count_data - $userUsage->count_data),
                'count_extend_data' => $countExtendData
            ]);
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function updateUserForAdmin(Array $userData, int $companyId, int $userId)
    {
        $company = $this->_companyRepository->with(['users.userUsage', 'companyUsage', 'extendPackage'])->find($companyId);
        if ($company == null) {
            throw new NotFoundException('messages.company_id_is_incorrect');
        }
        $user = $company->users->find($userId);
        if ($user == null) {
            throw new NotFoundException('messages.user_id_is_incorrect');
        }
        $isSubUser = $this->_commonService->checkPermission(json_decode($user->userRole->permissions, true), Permission::SUB_USER);
        if ($isSubUser) {
            $dataUpdate = $this->_generateDataUpdateSubUser($company, $user, $userData);
        } else {
            $dataUpdate = $this->_generateDataUpdateUser($company, $user, $userData);
        }

        try {
            if (!empty($userData['password'])) {
                $dataUpdate['data_user']['password'] = Hash::make($userData['password']);
            }
            if (!empty($userData['avatar_path'])) {
                $dataUpdate['data_user']['avatar_path'] = $userData['avatar_path'];
                $oldAvatar = $user->avatar_path;
            }
            $this->beginTransaction();
            $user->update($dataUpdate['data_user']);
            if (!empty($dataUpdate['data_user_usage'])) {
                $this->_dataUsageStatisticService->updateUserUsage($user->userUsage, $dataUpdate['data_user_usage']);
            }
            if (!empty($dataUpdate['data_company_usage'])) {
                $this->_dataUsageStatisticService->updateCompanyUsage($company->companyUsage, $dataUpdate['data_company_usage']);
            }
            $this->commitTransaction();
            if (!empty($oldAvatar)) {
                $this->_uploadMediaService->deleteMedia($oldAvatar, Disk::USER);
            }
            return $user;
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw $exception;
        }
    }

    public function updateUserForAdminSCSoft(Array $userData, int $userId)
    {
        $user = $this->_userRepository->find($userId);
        if ($user == null) {
            throw new NotFoundException('messages.user_id_is_incorrect');
        }
        return $this->updateUserForAdmin($userData, $user->company_id, $userId);
    }

    public function getAllUser(int $limit, Array $paramQuery)
    {
        return $this->_userRepository
            ->pushCriteria(new SearchUsersCriteria($paramQuery))
            ->with(['company', 'userUsage'])
            ->paginate($limit);
    }

    public function getAllRole(int $limit, int $companyId, Array $paramQuery){
        return $this->_userRoleRepository
            ->pushCriteria(new SearchRolesCriteria($paramQuery))
            ->where('company_id', '=', $companyId)
            ->paginate($limit);
    }

    public function getListUser(int $companyId, Array $paramQuery, int $limit)
    {
        return $this->_userRepository
            ->where('company_id', '=', $companyId)
            ->pushCriteria(new AdminCompanySearchUserCriteria($paramQuery))
            ->with(['company', 'userUsage', 'userRole'])
            ->paginate($limit);
    }

    private function _checkAvailableChangeUserTypeToSubUser(CompanyModel $companyEntity, UserModel $userEntity, int $userCreatedId)
    {
        $userCreated = $companyEntity->users()->find($userCreatedId);
        if (!$userCreated || !$userCreated->userRole || $this->_commonService->isSubUser(json_decode($userCreated->userRole->permissions ?? '[]', true)) || $userCreated->id === $userEntity->id) {
            throw new UnprocessableException('messages.management_user_is_incorrect');
        }
        $userCreatedUsage = $userCreated->userUsage;
        $subUsers = $userCreated->subUsers->filter(function ($user) {
            return $this->_commonService->isSubUser(json_decode($user->userRole->permissions ?? '[]', true));
        });
        $maxDataAccept = $userCreatedUsage->package_data + $userCreatedUsage->extend_data - $userCreatedUsage->count_data;
        if ($subUsers->isNotEmpty()) {
            foreach ($subUsers as $subUser) {
                if ($subUser->id != $userEntity->id) {
                    $maxDataAccept = $maxDataAccept - $subUser->userUsage->count_data;
                }
            }
        }
        if ($userEntity->userUsage->count_data > $maxDataAccept) {
            throw new ForbiddenException('messages.management_used_storage_capacity_has_exceeded_limit');
        }
    }
    private function _generateDataUpdateUser(CompanyModel $companyEntity,UserModel $userEntity, Array $userData)
    {
        $userUsage = $userEntity->userUsage;
        $companyUsage = $companyEntity->companyUsage;
        $dataUpdateUser = Arr::only($userData, ['staff_code', 'full_name', 'email', 'address', 'department', 'position', 'role_id']);
        $dataUpdateUserUsage = [];
        $dataUpdateCompanyUsage = [];
        if (array_key_exists('role_id', $userData) && $userData['role_id']) {
            $roleEntity = $this->_userRoleRepository->where('company_id', '=', $companyEntity->id)->find($userData['role_id']);
            if ($roleEntity == null) {
                throw new NotFoundException('messages.user_role_is_incorrect');
            }
            $isRoleSubUser = $this->_commonService->checkPermission(json_decode($roleEntity->permissions, true), Permission::SUB_USER);
            if ($isRoleSubUser) {
                if ($userEntity->subUsers->count() > 0) {
                    throw new ForbiddenException('messages.cannot_add_management_account_for_user');
                }
                $this->_checkAvailableChangeUserTypeToSubUser($companyEntity, $userEntity, $userData['user_created_id']);
                $dataUpdateUser['user_created_id'] = $userData['user_created_id'];
                $dataUpdateUserUsage['extend_data'] = 0;
                $dataUpdateCompanyUsage['count_user'] = $companyUsage->count_user - 1;
                $dataUpdateCompanyUsage['count_extend_data'] = $companyUsage->count_extend_data - $userUsage->extend_data;
            } else {
                if (isset($userData['extend_size'])) {
                    $extendSize =  $userData['extend_size'];
                    $extendSize = $this->_handleResourceService->convertGigaByteToByte($extendSize);
                    $this->_packageService->checkAvailableChangeExtendData($extendSize, $userEntity, $companyEntity);
                    $dataUpdateUserUsage['extend_data'] = $extendSize;
                    $dataUpdateCompanyUsage['count_extend_data'] = $companyUsage->count_extend_data - $userUsage->extend_data + $extendSize;
                }
            }
        }
        return [
            'data_user'             =>  $dataUpdateUser,
            'data_user_usage'       =>  $dataUpdateUserUsage,
            'data_company_usage'    =>  $dataUpdateCompanyUsage
        ];
    }

    private function _generateDataUpdateSubUser(CompanyModel $companyEntity, UserModel $userEntity, Array $userData)
    {
        $userUsage = $userEntity->userUsage;
        $companyUsage = $companyEntity->companyUsage;
        $dataUpdateUser = Arr::only($userData, ['staff_code', 'full_name', 'email', 'address', 'department', 'position', 'role_id']);
        $dataUpdateUserUsage = [];
        $dataUpdateCompanyUsage = [];
        if (array_key_exists('role_id', $userData) && $userData['role_id']) {
            $roleEntity = $this->_userRoleRepository->where('company_id', '=', $companyEntity->id)->find($userData['role_id']);
            if ($roleEntity == null) {
                throw new NotFoundException('messages.user_role_is_incorrect');
            }
            $isRoleSubUser = $this->_commonService->checkPermission(json_decode($roleEntity->permissions, true), Permission::SUB_USER);
            if (!$isRoleSubUser) {
                if ($companyUsage->count_user >= $companyEntity->servicePackage->max_user) {
                    throw new ForbiddenException('messages.number_of_users_has_exceeded_limit');
                }
                if ($userUsage->count_data > $companyEntity->servicePackage->max_user_data) {
                    throw new ForbiddenException('messages.used_storage_capacity_has_exceeded_limit');
                }
                $dataUpdateUser['user_created_id'] = null;
                $dataUpdateCompanyUsage['count_user'] = $companyUsage->count_user + 1;
            } else {
                if ($userData['user_created_id'] !== $userEntity->user_created_id) {
                    $this->_checkAvailableChangeUserTypeToSubUser($companyEntity, $userEntity, $userData['user_created_id']);
                }
                $dataUpdateUser['user_created_id'] = $userData['user_created_id'];
            }
        }
        return [
            'data_user'             =>  $dataUpdateUser,
            'data_user_usage'       =>  $dataUpdateUserUsage,
            'data_company_usage'    =>  $dataUpdateCompanyUsage
        ];
    }

    public function deleteUserForAdminCompany(int $userId, int $userTargetId)
    {
        $user = $this->_userRepository->with(['company.companyUsage', 'userUsage', 'albums', 'subUsers'])->find($userId);
        if (!$user) {
            throw new NotFoundException('messages.user_does_not_exist');
        }
        $userTarget = $this->_userRepository->with(['company.companyUsage', 'userUsage'])->find($userTargetId);
        if (!$userTarget|| $userTargetId === $userId) {
            throw new ForbiddenException('messages.user_manager_album_is_incorrect');
        }
        $this->_dataUsageStatisticService->checkConvertDataUserInCompany($user, $userTarget);
        try {
            $this->beginTransaction();
            $this->_convertDataUserInCompany($user, $userTarget);
            $companyUsage = $user->company->companyUsage;
            $countUsers = $companyUsage->count_user;
            if (!$this->_commonService->isSubUser(json_decode($user->userRole->permissions ?? '[]', true))) {
                $countUsers = $countUsers - 1;
            }
            $user->fresh()->delete();
            $this->_dataUsageStatisticService->updateCompanyUsage($companyUsage, [
                'count_user' => $countUsers
            ]);
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    private function _convertDataUserInCompany(UserModel $userConvert, UserModel $userTarget)
    {
        $albumConverts = $userConvert->albums;
        if ($albumConverts->isNotEmpty()) {
            foreach ($albumConverts as $albumConvert) {
                $albumConvert->update([
                    "user_id" => $userTarget->id
                ]);
            }
        }
        $subUserConverts = $userConvert->subUsers;
        if ($subUserConverts->isNotEmpty()) {
            foreach ($subUserConverts as $subUserConvert) {
                $subUserConvert->update([
                    "user_created_id" => $userTarget->id
                ]);
            }
        }
        $userConvertUsage =  $userConvert->userUsage;
        $userTargetUsage =  $userTarget->userUsage;
        $userTargetUsage->update([
            "count_album" => $userTargetUsage->count_album + $userConvertUsage->count_album,
            "count_data" => $userTargetUsage->count_data + $userConvertUsage->count_data,
            "extend_data" => $userTargetUsage->extend_data + $userConvertUsage->extend_data
        ]);
    }
}
