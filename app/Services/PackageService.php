<?php

namespace App\Services;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnprocessableException;
use App\Models\CompanyModel;
use App\Models\ExtendPackageModel;
use App\Models\ServicePackageModel;
use App\Models\UserModel;
use App\Models\UserUsageModel;
use App\Repositories\Criteria\SearchExtendPackageCriteria;
use App\Repositories\Criteria\SearchServicePackageCriteria;
use App\Repositories\Repository;
use Illuminate\Support\Arr;

class PackageService extends AbstractService
{
    private $_servicePackageRepository;
    private $_extendPackageRepository;
    private $_userUsageRepository;
    private $_handleResourceRepository;
    private $_commonService;

    public function __construct (
        ServicePackageModel $servicePackageModel,
        ExtendPackageModel $extendPackageModel,
        UserUsageModel $userUsageModel,
        HandleResourceService $handleResourceService,
        CommonService $commonService
    )
    {
        $this->_servicePackageRepository = new Repository($servicePackageModel);
        $this->_extendPackageRepository = new Repository($extendPackageModel);
        $this->_userUsageRepository = new Repository($userUsageModel);
        $this->_handleResourceRepository = $handleResourceService;
        $this->_commonService = $commonService;
    }

    public function checkAvailableChangeServicePackage(int $serviceIdCheck, CompanyModel $company)
    {
        $newServicePackage = $this->_servicePackageRepository->find($serviceIdCheck);
        if ($newServicePackage == null) {
            throw new UnprocessableException('messages.service_package_does_not_exist');
        }
        $users = $company->users->filter(function ($user) {
            return !$this->_commonService->isSubUser(json_decode($user->userRole->permissions ?? '[]', true));
        });
        if ($newServicePackage->max_user < $users->count()) {
            throw new ForbiddenException('messages.number_of_users_has_exceeded_limit');
        }
        foreach ($users as $user) {
            $userUsage = $user->userUsage;
            $usedStorageCapacityOfServicePackage = $userUsage->count_data - $userUsage->extend_data;
            if ($newServicePackage->max_user_data < $usedStorageCapacityOfServicePackage) {
                throw new ForbiddenException('messages.storage_capacity_of_company_is_not_enough');
            }
        }
    }

    public function checkAvailableChangeExtendPackage(int $extendIdCheck, CompanyModel $company)
    {
        $newExtendPackage = $this->_extendPackageRepository->find($extendIdCheck);
        if ($newExtendPackage == null) {
            throw new UnprocessableException('messages.extend_package_does_not_exist');
        }
        if ($newExtendPackage->extend_data < $company->companyUsage->count_extend_data) {
            throw new ForbiddenException('messages.used_storage_capacity_has_exceeded_limit');
        }
    }

    public function updatePackageDataOfUserUsage(int $servicePackageId, CompanyModel $company)
    {
        $servicePackage = $this->_servicePackageRepository->find($servicePackageId);
        if ($servicePackage == null) {
            throw new UnprocessableException('messages.service_package_does_not_exist');
        }
        $where = [];
        foreach ($company->users as $user) {
            $where[] = ['user_id', '=', $user->id];
        }
        $this->_userUsageRepository->updateOr($where, ['package_data' => $servicePackage->max_user_data]);
    }

    public function checkAvailableChangeExtendData(int $size, UserModel $user, CompanyModel $company)
    {
        if ($size > 0) {
            if ($company->extendPackage == null) {
                throw new ForbiddenException('messages.company_has_not_yet_purchased_the_Extend_Package');
            }
            if (($company->extendPackage->extend_data - $company->companyUsage->count_extend_data + $user->userUsage->extend_data) < $size) {
                throw new ForbiddenException('messages.exceeded_the_expansion_package');
            }
            if ($user->userUsage->package_data + $size < $user->userUsage->count_data) {
                throw new ForbiddenException('messages.user_has_used_exceeding_the_expansion_pack_limit');
            }
        }
    }

    public function getListServicePackages(int $limit, Array $paramQuery)
    {
        return $this->_servicePackageRepository
            ->pushCriteria(new SearchServicePackageCriteria($paramQuery))
            ->with(['companies'])
            ->paginate($limit);
    }
    public function getAllListServicePackages()
    {
        return $this->_servicePackageRepository->all(['id','title','max_user','price','max_user_data','description']);
    }

    public function getServicePackage(int $packageId)
    {
        $package = $this->_servicePackageRepository->find($packageId);

        if ($package == null) {
            throw new NotFoundException('messages.service_package_does_not_exist');
        }
        return $package;
    }

    public function createServicePackage(Array $packageData)
    {
        $packageData['max_user_data'] = $this->_handleResourceRepository->convertGigaByteToByte($packageData['max_user_data']);
        $countPackage = $this->_servicePackageRepository
            ->where('max_user', '=', $packageData['max_user'])
            ->where('max_user_data', '=', $packageData['max_user_data'])
            ->count();
        if ($countPackage > 0) {
            throw new ForbiddenException('messages.service_package_already_exist');
        }
        return $this->_servicePackageRepository->create($packageData);
    }

    public function updateServicePackage(Array $packageData, int $packageId)
    {
        $packageEntity = $this->_servicePackageRepository->with(['companies'])->find($packageId);
        if ($packageEntity == null) {
            throw new NotFoundException('messages.service_package_does_not_exist');
        }
        if (empty($packageData)) {
            throw new UnprocessableException('messages.nothing_to_update');
        }
        if ($packageEntity->companies->count() > 0) {
            throw new ForbiddenException('messages.package_cannot_be_edited');
        }
        if (!empty($packageData['max_user'])) {
            $newMaxUser = $packageData['max_user'];
        } else {
            $newMaxUser = $packageEntity->max_user;
        }
        if (!empty($packageData['max_user_data'])) {
            $packageData['max_user_data'] = $this->_handleResourceRepository->convertGigaByteToByte($packageData['max_user_data']);
            $newMaxUserData = $packageData['max_user_data'];
        } else {
            $newMaxUserData = $packageEntity->max_user_data;
        }
        $countPackage = $this->_servicePackageRepository
            ->where('max_user', '=', $newMaxUser)
            ->where('max_user_data', '=', $newMaxUserData)
            ->whereNotIn('id', [$packageEntity->id])
            ->count();
        if ($countPackage > 0) {
            throw new ForbiddenException('messages.service_package_already_exist');
        }
        $packageEntity->update($packageData);
        return $packageEntity;
    }

    public function deleteServicePackage(int $packageId)
    {
        $packageEntity = $this->_servicePackageRepository->with(['companies'])->find($packageId);
        if ($packageEntity == null) {
            throw new NotFoundException('messages.service_package_does_not_exist');
        }
        if ($packageEntity->companies->count() > 0) {
            throw new ForbiddenException('messages.package_cannot_be_deleted');
        }
        $packageEntity->delete();
    }

    public function getListExtendPackages(int $limit, Array $paramQuery)
    {
        return $this->_extendPackageRepository
            ->pushCriteria(new SearchExtendPackageCriteria($paramQuery))
            ->with(['companies'])
            ->paginate($limit);
    }

    public function getExtendPackage(int $extendId)
    {
        $extend = $this->_extendPackageRepository->find($extendId);

        if ($extend == null) {
            throw new NotFoundException('messages.extend_package_does_not_exist');
        }
        return $extend;
    }

    public function createExtendPackage(Array $extendData)
    {
        $extendData['extend_data'] = $this->_handleResourceRepository->convertGigaByteToByte($extendData['extend_data']);
        $countExtend = $this->_extendPackageRepository
            ->where('extend_user', '=', $extendData['extend_user'])
            ->where('extend_data', '=', $extendData['extend_data'])
            ->count();
        if ($countExtend > 0) {
            throw new ForbiddenException('messages.extend_package_already_exist');
        }
        return $this->_extendPackageRepository->create($extendData);
    }

    public function updateExtendPackage(Array $extendData, int $extendId)
    {
        $extendEntity = $this->_extendPackageRepository->with(['companies'])->find($extendId);
        if ($extendEntity == null) {
            throw new NotFoundException('messages.extend_package_does_not_exist');
        }
        if (empty($extendData)) {
            throw new UnprocessableException('messages.nothing_to_update');
        }
        if ($extendEntity->companies->count() > 0) {
            throw new ForbiddenException('messages.extend_package_cannot_be_edited');
        }
        if (isset($extendData['extend_user'])) {
            $newExtendUser = $extendData['extend_user'];
        } else {
            $newExtendUser = $extendEntity->extend_user;
        }
        if (isset($extendData['extend_data'])) {
            $extendData['extend_data'] = $this->_handleResourceRepository->convertGigaByteToByte($extendData['extend_data']);
            $newExtendData = $extendData['extend_data'];
        } else {
            $newExtendData = $extendEntity->extend_data;
        }
        $countExtend = $this->_extendPackageRepository
            ->where('extend_user', '=', $newExtendUser)
            ->where('extend_data', '=', $newExtendData)
            ->whereNotIn('id', [$extendEntity->id])
            ->count();
        if ($countExtend > 0) {
            throw new ForbiddenException('messages.extend_package_already_exist');
        }
        $extendEntity->update($extendData);
        return $extendEntity;
    }

    public function deleteExtendPackage(int $extendId)
    {
        $extendEntity = $this->_extendPackageRepository->with(['companies'])->find($extendId);
        if ($extendEntity == null) {
            throw new NotFoundException('messages.extend_package_does_not_exist');
        }
        if ($extendEntity->companies->count() > 0) {
            throw new ForbiddenException('messages.extend_package_cannot_be_deleted');
        }
        $extendEntity->delete();
    }
}
