<?php

namespace App\Services;

use App\Constants\CategorySampleContract;
use App\Constants\ContractStatus;
use App\Http\Resources\WebResources\TopAlbumLocationCommentResource;
use App\Http\Resources\WebResources\TopAlbumLocationMediaCommentResource;
use App\Models\AlbumLocationCommentModel;
use App\Models\AlbumLocationMediaCommentModel;
use App\Models\AppVersionModel;
use App\Models\CompanyModel;
use App\Models\CompanyUsageModel;
use App\Models\ContractModel;
use App\Models\DepartmentModel;
use App\Models\ExtendPackageModel;
use App\Models\PdfContentTemplateModel;
use App\Models\PositionModel;
use App\Models\ServicePackageModel;
use App\Models\UserModel;
use App\Models\UserRoleModel;
use App\Repositories\Criteria\FilterBetweenDaySignedDateCriteria;
use App\Repositories\Criteria\FilterBetweenMonthSignedDateCriteria;
use App\Repositories\Criteria\FilterServicePackageBySignedDateCriteria;
use App\Repositories\Repository;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OtherService extends AbstractService
{
    protected $_departmentRepository;
    protected $_positionRepository;
    protected $_servicePackageRepository;
    protected $_extendPackageRepository;
    protected $_companyRepository;
    protected $_handleResourceService;
    protected $_albumService;
    protected $_userService;
    protected $_albumLocationService;
    protected $_albumLocationMediaService;
    protected $_commentService;
    protected $userRoleRepository;
    protected $appVersionRepository;
    protected $_commonService;
    protected $userRepository;
    protected $pdfContentTemplatesRepository;
    protected $_contractRepository;
    protected $_companyUsageRepository;
    public function __construct(
        DepartmentModel $departmentModel,
        PositionModel $positionModel,
        ServicePackageModel $servicePackageModel,
        ExtendPackageModel $extendPackageModel,
        CompanyModel $companyModel,
        HandleResourceService $handleResourceService,
        AlbumService $albumService,
        UserService $userService,
        AlbumLocationService $albumLocationService,
        AlbumLocationMediaService $albumLocationMediaService,
        CommentService $commentService,
        UserRoleModel $userRoleModel,
        AppVersionModel $appVersionModel,
        CommonService $commonService,
        UserModel $userModel,
        PdfContentTemplateModel $pdfContentTemplateModel,
        ContractModel $contractModel,
        CompanyUsageModel $companyUsageModel
    )
    {
        $this->_departmentRepository = new Repository($departmentModel);
        $this->_positionRepository = new Repository($positionModel);
        $this->_servicePackageRepository = new Repository($servicePackageModel);
        $this->_extendPackageRepository = new Repository($extendPackageModel);
        $this->_companyRepository = new Repository($companyModel);
        $this->_contractRepository = new Repository($contractModel);
        $this->_companyUsageRepository = new Repository($companyUsageModel);
        $this->_handleResourceService = $handleResourceService;
        $this->_albumService = $albumService;
        $this->_userService = $userService;
        $this->_albumLocationService = $albumLocationService;
        $this->_albumLocationMediaService = $albumLocationMediaService;
        $this->_commentService = $commentService;
        $this->userRoleRepository = new Repository($userRoleModel);
        $this->appVersionRepository = new Repository($appVersionModel);
        $this->_commonService = $commonService;
        $this->userRepository = new Repository($userModel);
        $this->pdfContentTemplatesRepository = new Repository($pdfContentTemplateModel);
    }

    public function getDepartments()
    {
        return $this->_departmentRepository->all(['id', 'title']);
    }

    public function getPositions()
    {
        return $this->_positionRepository->all(['id', 'title']);
    }

    public function getServicePackages()
    {
        return $this->_servicePackageRepository->all(['id', 'title']);
    }

    public function getExtendPackages()
    {
        return $this->_extendPackageRepository->all(['id', 'title']);
    }
    private function getSignedContractsNumber(Carbon $paramStart , Carbon $paramEnd)
    {
        $param1 = $paramStart;
        $param2 = $paramEnd;
        $this->_contractRepository->resetCriteria();
        $data['this_month'] = $this->_contractRepository->pushCriteria(new FilterBetweenDaySignedDateCriteria($paramStart,$paramEnd))->with(['sampleContract'])->all()->count();
        $this->_contractRepository->resetCriteria();
        $data['last_month'] = $this->_contractRepository->pushCriteria(new FilterBetweenMonthSignedDateCriteria( $param1,$param2))->with(['sampleContract'])->all()->count();
        $this->_contractRepository->resetCriteria();
        return $data;
    }
    private function getMemberTotal(Carbon $paramStart , Carbon $paramEnd)
    {
        $contracts = $this->_contractRepository->pushCriteria(new FilterBetweenDaySignedDateCriteria($paramStart,$paramEnd))->with(['sampleContract'])->all();
        $this->_contractRepository->resetCriteria();
        $data['sum_user'] = 0;
        $data['sum_data'] = 0;
        $index = 0;
        $company['companies'][][] = null;
        $arrayId[] = null;
        foreach ($contracts as $contract)
        {
            $companyUsageEntity = $contract->company->companyUsage()->select('count_data','count_user')->first();
            if(in_array($contract->company->id,$arrayId)){
                $data['sum_data'] += $companyUsageEntity ? $companyUsageEntity->count_data : 0;
                $data['sum_user'] += $companyUsageEntity ? $companyUsageEntity->count_user : 0;
                continue;
            }
            $company['companies'][$index]['id'] = $contract->company->id;
            $company['companies'][$index]['company_name'] = $contract->company->company_name;
            $company['companies'][$index]['service_name'] = $contract->servicePackage->title;
            $company['companies'][$index]['users_used'] = $contract->company->companyUsage->count_user;
            $maxUser = $contract->servicePackage->max_user;
            $maxUserData = $contract->servicePackage->max_user_data;
            $company['companies'][$index]['max_user'] = $maxUser;
            $company['companies'][$index]['max_user_data'] = $maxUserData;
            $company['companies'][$index]['max_data'] = $maxUser * $maxUserData;
            $data['sum_data'] += $companyUsageEntity ? $companyUsageEntity->count_data : 0;
            $data['sum_user'] += $companyUsageEntity ? $companyUsageEntity->count_user : 0;
            $index +=1;
            array_push($arrayId, $contract->company->id);
        }
        $data['companies'] = $company['companies'];
        if($data['sum_data'] === 0){
            return $data;
        }
        $data['sum_data'] = $this->_handleResourceService->convertByteToGigaByte($data['sum_data']);
        return $data;
    }

    private function getListServicePackage(Carbon $paramStart , Carbon $paramEnd)
    {
        $this->_contractRepository->resetCriteria();
        $contracts = $this->_contractRepository->pushCriteria(new FilterServicePackageBySignedDateCriteria($paramStart,$paramEnd))->with(['sampleContract','servicePackage'])->all();
        $servicePackages = $this->_servicePackageRepository->all();
        foreach ($servicePackages as $index => $service) {
            $services[$index]['count_user'] = 0;
            foreach ($contracts as $indexContract => $contract) {
                if ($service['id'] === $contract['service_package_id']) {
                    $servicePackages[$index]['use_total'] = $contract['sum_user'];
                    $servicePackages[$index]['sum_data'] = $this->_handleResourceService->convertByteToGigaByte($contract['sum_data']);
                    $servicePackages[$index]['companies_use_total'] = $contract['companies_use_total'];
                }
            }
        }
        return $servicePackages;
    }

    public function getListCompanies()
    {
        return $this->_companyRepository->all(['id', 'company_name', 'company_code','service_id','color','logo_path','address','extend_id','representative','tax_code']);
    }

    public function getListUsers()
    {
        $currentUser = app('currentUser');
        $users = $currentUser->company->users()->get();
        $userResponse = [];
        foreach ($users as $user) {
            if (!$this->_commonService->isSubUser(json_decode($user->userRole->permissions ?? '[]', true))) {
                $userResponse[] = [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'email' => $user->email
                ];
            }
        }
        return $userResponse;
    }

    public function getListAllUsers()
    {
        $currentUser = app('currentUser');
        return $currentUser->company->users()->select('id', 'full_name', 'email')->get()->toArray();
    }

    public function getDashboard(array $paramQuery)
    {
        try {
            $currentUser = app('currentUser');
            $paramQuery['start_day'] = $paramQuery['start_day'] ? (new Carbon((int)$paramQuery['start_day'])) : (new Carbon(0));
            $paramQuery['end_day'] = $paramQuery['end_day'] ? (new Carbon((int)$paramQuery['end_day'])) : today();

            if ($this->_commonService->isAdmin($currentUser)) {
                return $this->_generateAdminDashboard($currentUser, $paramQuery);
            } else if (!$this->_commonService->isSubUser(json_decode($currentUser->userRole->permissions ?? '[]', true))){
                return $this->_generateUserDashboard($currentUser, $paramQuery);
            }
            return null;
        } catch (\Exception $exception) {
            report($exception);
            throw $exception;
        }
    }
    public function getDashboardForAdmin(?array $paramQuery)
    {
        try{
            if(!$paramQuery){
                $lastMonth = (int)(strtotime(now()->modify('-1 month')));
                $paramQuery['start_day'] = new Carbon(new Carbon($lastMonth));
                $paramQuery['end_day'] = new Carbon(now());
            }else{
                $paramQuery['start_day'] = new Carbon((int)$paramQuery['start_day']);
                $paramQuery['end_day'] = new Carbon((int)$paramQuery['end_day']);
            }
            return $this->_generateAdminSystemDashboard($paramQuery['start_day'],$paramQuery['end_day']);
        }catch (\Exception $exception){
            report($exception);
            throw $exception;
        }
    }
    private function _generateAdminSystemDashboard(Carbon $paramStart ,Carbon $paramEnd)
    {
        $data = [];
        $data['member'] = $this->getMemberTotal($paramStart,$paramEnd);
        $data['service_package'] = $this->getListServicePackage($paramStart,$paramEnd);
        $data['signed_contracts'] = $this->getSignedContractsNumber($paramStart,$paramEnd);
        return $data;
    }
    private function _generateAdminDashboard(UserModel $user, array $paramQuery)
    {
        $data = [];
        $data['data_usage'] = $this->_generateDataUsageForAdmin($user, $paramQuery);
        $data['data_top_comments'] = $this->_generateTopCommentsForAdmin($user, $paramQuery);
        $data['data_top_users'] = $this->_generateTopUserForAdmin($user, $paramQuery);
        $data['data_admin_management_users'] = $this->_generateUserManagementForAdmin($user, $paramQuery);
        return $data;
    }

    private function _generateUserDashboard(UserModel $user, array $paramQuery)
    {
        $data = [];
        $data['data_usage'] = $this->_generateDataUsageForUser($user, $paramQuery);
        $data['data_top_comments'] = $this->_generateTopCommentsForUser($user, $paramQuery);
        $data['data_top_users'] = $this->_generateTopSubUserForUser($user, $paramQuery);
        return $data;
    }

    private function _generateDataUsageForAdmin(UserModel $user, array $paramQuery)
    {
        $dataUsage = [];
        $company = $user->company;
        $userUsage = $user->userUsage;
        $subUsers = $this->_userService->getListSubUsersByBetweenDays($user->id, $paramQuery);
        $users = $this->_userService->getListUsersByBetweenDays($company->id, $paramQuery);
        $userIds = $users->pluck('id')->all();
        $albums = $this->_albumService->getListAlbumsByBetweenDays($userIds, $paramQuery);
        $dataUsage['number_albums'] = $albums->count();
        $dataUsage['number_users'] = $users->count();
        $dataUsage['used_size'] = $this->_handleResourceService->convertByteToGigaByte($userUsage->count_data + $subUsers->sum(function ($item) {
            return $item->userUsage->count_data;
            })
        );
        $dataUsage['used_size_percent'] = round(100 * $dataUsage['used_size'] / ($this->_handleResourceService->convertByteToGigaByte($userUsage->package_data + $userUsage->extend_data)), 2);
        return $dataUsage;
    }

    private function _generateDataUsageForUser(UserModel $user, array $paramQuery)
    {
        $dataUsage = [];
        $userUsage = $user->userUsage;
        $userIds = [$user->id];
        $subUsers = $this->_userService->getListSubUsersByBetweenDays($user->id, $paramQuery);
        $subUserIds = $subUsers->pluck('id')->all();
        $userIds = array_merge($userIds, $subUserIds);
        $albums = $this->_albumService->getListAlbumsByBetweenDays($userIds, $paramQuery);
        $dataUsage['number_albums'] = $albums->count();
        $dataUsage['number_users'] = $subUsers->count() + 1;
        $dataUsage['used_size'] = $this->_handleResourceService->convertByteToGigaByte($userUsage->count_data + $subUsers->sum(function ($item) {
                return $item->userUsage->count_data;
            })
        );
        $dataUsage['used_size_percent'] = round(100 * $dataUsage['used_size'] / ($this->_handleResourceService->convertByteToGigaByte($userUsage->package_data + $userUsage->extend_data)), 2);
        return $dataUsage;
    }

    private function _generateTopCommentsForAdmin(UserModel $user, array $paramQuery)
    {
        $company = $user->company;
        $users = $this->_userService->getListUsersByBetweenDays($company->id, $paramQuery);
        $userIds = $users->pluck('id')->all();
        $albums = $this->_albumService->getListAlbumsByBetweenDays($userIds, $paramQuery);
        $albumIds = $albums->pluck('id')->all();
        $locations = $this->_albumLocationService->getListLocationByBetweenDays($albumIds, $paramQuery);
        $locationIds = $locations->pluck('id')->all();
        $medias = $this->_albumLocationMediaService->getListMediasByBetweenDays($locationIds, $paramQuery);
        $mediaIds = $medias->pluck('id')->all();
        $topLocationComments = $this->_commentService->getTopLocationCommentsByBetweenDays($locationIds, $paramQuery);
        $topMediaComments = $this->_commentService->getTopMediaCommentsByBetweenDays($mediaIds, $paramQuery);
        return $this->_generateTopComments($topLocationComments, $topMediaComments);
    }

    private function _generateTopCommentsForUser(UserModel $user, array $paramQuery)
    {
        $userIds = [$user->id];
        $subUsers = $this->_userService->getListSubUsersByBetweenDays($user->id, $paramQuery);
        $subUserIds = $subUsers->pluck('id')->all();
        $userIds = array_merge($userIds, $subUserIds);
        $albums = $this->_albumService->getListAlbumsByBetweenDays($userIds, $paramQuery);
        $albumIds = $albums->pluck('id')->all();
        $locations = $this->_albumLocationService->getListLocationByBetweenDays($albumIds, $paramQuery);
        $locationIds = $locations->pluck('id')->all();
        $medias = $this->_albumLocationMediaService->getListMediasByBetweenDays($locationIds, $paramQuery);
        $mediaIds = $medias->pluck('id')->all();
        $topLocationComments = $this->_commentService->getTopLocationCommentsByBetweenDays($locationIds, $paramQuery);
        $topMediaComments = $this->_commentService->getTopMediaCommentsByBetweenDays($mediaIds, $paramQuery);
        return $this->_generateTopComments($topLocationComments, $topMediaComments);
    }

    private function _generateTopComments(Collection $topLocationComments, Collection $topMediaComments)
    {
        $dataTopComments = [];
        $topComments = new Collection([]);
        $topComments = $topComments->merge($topLocationComments);
        $topComments = $topComments->merge($topMediaComments);
        $topComments = $topComments->sortByDesc('created_at')->values()->take(5);
        if ($topComments->isNotEmpty()) {
            foreach ($topComments as $comment) {
                if ($comment instanceof AlbumLocationCommentModel) {
                    $dataTopComments[] = new TopAlbumLocationCommentResource($comment);
                }
                if ($comment instanceof AlbumLocationMediaCommentModel) {
                    $dataTopComments[] = new TopAlbumLocationMediaCommentResource($comment);
                }
            }
        }
        return $dataTopComments;
    }

    private function _generateTopUserForAdmin(UserModel $user, array $paramQuery)
    {
        $company = $user->company;
        $users = $this->_userService->getListUsersByBetweenDays($company->id, $paramQuery);
        $users->filter(function ($user){
           return (!$this->_commonService->isAdmin($user) && !$this->_commonService->isSubUser(json_decode($user->userRole->permissions ?? '[]', true)));
        });
        $users = $users->sortByDesc(function ($item) use ($paramQuery) {
            $subUserIds = $this->_userService->getListSubUsersByBetweenDays($item->id, $paramQuery)->pluck('id')->all();
            $userIds = array_merge([$item->id], $subUserIds);
            return $this->_albumService->getListSharedAlbumsByBetweenDays($userIds, $paramQuery)->count();
        })->sortByDesc(function ($item) use ($paramQuery) {
            $subUserIds = $this->_userService->getListSubUsersByBetweenDays($item->id, $paramQuery)->pluck('id')->all();
            $userIds = array_merge([$item->id], $subUserIds);
            return $this->_albumService->getListAlbumsByBetweenDays($userIds, $paramQuery)->count();
        })->values()->take(5);
        return $this->_generateDataTopUserForAdmin($users, $paramQuery);
    }

    private function _generateTopSubUserForUser(UserModel $user, array $paramQuery)
    {
        $subUsers = $this->_userService->getListSubUsersByBetweenDays($user->id, $paramQuery);
        $subUsers = $subUsers->sortByDesc(function ($item) use ($paramQuery) {
            return $this->_albumService->getListSharedAlbumsByBetweenDays([$item->id], $paramQuery)->count();
        })->sortByDesc(function ($item) use ($paramQuery) {
            return $this->_albumService->getListAlbumsByBetweenDays([$item->id], $paramQuery)->count();
        })->values()->take(5);
        return $this->_generateDataTopSubUserForUser($subUsers, $paramQuery);
    }

    private function _generateDataTopUserForAdmin(Collection $users, array $paramQuery)
    {
        $dataTopUsers = [];
        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                $subUserIds = $this->_userService->getListSubUsersByBetweenDays($user->id, $paramQuery)->pluck('id')->all();
                $userIds = array_merge([$user->id], $subUserIds);
                $albums = $this->_albumService->getListAlbumsByBetweenDays($userIds, $paramQuery);
                $sharedAlbums = $this->_albumService->getListSharedAlbumsByBetweenDays($userIds, $paramQuery);
                $dataTopUsers[] = [
                    'id'                    =>  $user->id,
                    'name'                  =>  $user->full_name,
                    'email'                 =>  $user->email,
                    'number_albums'         =>  $albums->count(),
                    'number_shared_albums'  =>  $sharedAlbums->count(),
                    'used_size'             =>  $this->_handleResourceService->convertByteToGigaByte($albums->sum('size'))
                ];
            }
        }
        return $dataTopUsers;
    }

    private function _generateDataTopSubUserForUser(Collection $subUsers, array $paramQuery)
    {
        $dataTopSubUsers = [];
        if ($subUsers->isNotEmpty()) {
            foreach ($subUsers as $subUser) {
                $albums = $this->_albumService->getListAlbumsByBetweenDays([$subUser->id], $paramQuery);
                $sharedAlbums = $this->_albumService->getListSharedAlbumsByBetweenDays([$subUser->id], $paramQuery);
                $dataTopSubUsers[] = [
                    'id'                    =>  $subUser->id,
                    'name'                  =>  $subUser->full_name,
                    'email'                 =>  $subUser->email,
                    'number_albums'         =>  $albums->count(),
                    'number_shared_albums'  =>  $sharedAlbums->count(),
                    'used_size'             =>  $this->_handleResourceService->convertByteToGigaByte($albums->sum('size'))
                ];
            }
        }
        return $dataTopSubUsers;
    }

    private function _generateUserManagementForAdmin(UserModel $user, array $paramQuery)
    {
        $company = $user->company;
        $users = $this->_userService->getListUsersByBetweenDays($company->id, $paramQuery);
        $users = $users->sortByDesc(function ($item) use ($paramQuery) {
            return $this->_albumService->getListAlbumsByBetweenDays([$item->id], $paramQuery)->sum('size');
        })->values()->take(5);
        return $this->_generateDataManagementUserForUser($users);
    }

    private function _generateDataManagementUserForUser(Collection $users)
    {
        $dataUsers = [];
        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                $dataUsers[] = [
                    'id'        =>  $user->id,
                    'name'      =>  $user->full_name,
                    'email'     =>  $user->email,
                    'avatar'    =>  $user->avatar_url,
                    'role'      =>  $user->userRole->name
                ];
            }
        }
        return $dataUsers;
    }

    public function retrieveListRoleInCompany(int $companyId)
    {
        return $this->userRoleRepository->where('company_id', '=', $companyId)->all();
    }

    public function retrieveListAppVersions()
    {
        return $this->appVersionRepository->orderBy('created_at', 'DESC')->all(['id', 'name', 'en_description', 'ja_description', 'vi_description', 'active', 'created_at', 'version_ios', 'version_android']);
    }

    public function retrieveListAppVersion()
    {
        return $this->appVersionRepository->where('active', '=', 1)->first(['id', 'name', 'en_description', 'ja_description', 'vi_description', 'active', 'created_at', 'version_ios', 'version_android']);
    }

    public function getListUsersOfCompany(int $companyId)
    {
        $users = $this->userRepository->where('company_id', '=', $companyId)->all(['id', 'full_name', 'email']);
        $userResponse = new Collection([]);
        foreach ($users as $user) {
            if (!$this->_commonService->isSubUser(json_decode($user->userRole->permissions ?? '[]', true))) {
                $userResponse->push($user);
            }
        }
        return $userResponse->toArray();
    }

    public function getListPDFContentTemplates()
    {
        return $this->pdfContentTemplatesRepository->all(['id', 'name', 'description', 'html', 'image_no'])->toArray();
    }
}
