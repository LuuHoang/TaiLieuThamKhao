<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\CompanyModel;
use App\Models\UserModel;
use App\Models\UserRoleModel;
use App\Models\UserTokenModel;
use App\Repositories\Criteria\FilterBetweenDayCriteria;
use App\Repositories\Repository;
use App\Services\WebService\ContractService;

class UserService extends AbstractService
{
    protected $_userRoleRepository;
    protected $_userRepository;
    protected $_companyRepository;
    protected $_uploadMediaService;
    protected $_dataUsageStatisticService;
    protected $_packageService;
    protected $_handleResourceService;
    protected $_userTokenRepository;
    protected $_commonService;
    protected $_contractService;
    public function __construct(
        UserRoleModel $userRoleModel,
        UserModel $userModel,
        CompanyModel $companyModel,
        UploadMediaService $uploadMediaService,
        DataUsageStatisticService $dataUsageStatisticService,
        PackageService $packageService,
        HandleResourceService $handleResourceService,
        UserTokenModel $userTokenModel,
        CommonService $commonService,
        ContractService $contractService
    )
    {
        $this->_userRoleRepository = new Repository($userRoleModel);
        $this->_userRepository = new Repository($userModel);
        $this->_companyRepository = new Repository($companyModel);
        $this->_uploadMediaService = $uploadMediaService;
        $this->_dataUsageStatisticService = $dataUsageStatisticService;
        $this->_packageService = $packageService;
        $this->_handleResourceService = $handleResourceService;
        $this->_userTokenRepository = new Repository($userTokenModel);
        $this->_userRoleRepository = new Repository($userRoleModel);
        $this->_commonService = $commonService;
        $this->_contractService = $contractService;
    }

    public function getUser(int $userId)
    {
        $userEntity = $this->_userRepository->with(['userUsage', 'userRole'])->find($userId);
        if ($userEntity == null) {
            throw new NotFoundException('messages.user_does_not_exist');
        }
        return $userEntity;
    }

    public function getListUsersByBetweenDays(int $companyId, array $paramQuery)
    {
        return $this->_userRepository->pushCriteria(new FilterBetweenDayCriteria($paramQuery))
            ->where('company_id', '=', $companyId)->all();
    }

    public function getListSubUsersByBetweenDays(int $createdUserId, array $paramQuery)
    {
        $subUsers = $this->_userRepository->pushCriteria(new FilterBetweenDayCriteria($paramQuery))
            ->where('user_created_id', '=', $createdUserId)->all();
        return $subUsers->filter(function ($subUser){
            return $this->_commonService->isSubUser(json_decode($subUser->userRole->permissions ?? '[]', true));
        });

    }
}
