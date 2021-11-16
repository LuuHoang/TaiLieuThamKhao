<?php
namespace App\Services\APIServices;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Models\AlbumModel;
use App\Models\AlbumLocationModel;
use App\Repositories\Repository;
use App\Services\AbstractService;
use App\Services\CommonService;
use App\Services\DataUsageStatisticService;
use Carbon\Carbon;

class AlbumsService extends AbstractService
{
    /**
     * @var Repository
     */
    private $_albumRepository;
    /**
     * @var Repository
     */
    private $_albumLocationRepository;
    /**
     * @var Repository
     */
    private $_dataUsageStatisticService;

    private $_commonService;

    public function __construct(
        AlbumModel $albumModel,
        AlbumLocationModel $albumLocationModel,
        DataUsageStatisticService $dataUsageStatisticService,
        CommonService $commonService
    )
    {
        $this->_albumRepository = new Repository($albumModel);
        $this->_albumLocationRepository = new Repository($albumLocationModel);
        $this->_dataUsageStatisticService = $dataUsageStatisticService;
        $this->_commonService = $commonService;
    }

    public function getAlbumLocationDetail(int $userId, int $albumId, int $locationId)
    {
        try {
            $albumLocationEntity = $this->_albumLocationRepository
                ->with(['albumLocationMedias', 'album'])
                ->where('album_id', '=', $albumId)
                ->find($locationId);
            if ($albumLocationEntity == null) {
                throw new NotFoundException('messages.album_location_does_not_exist');
            }
            $album = $albumLocationEntity->album;
            return $albumLocationEntity;
        } catch (NotFoundException $exception) {
            throw $exception;
        } catch (ForbiddenException $exception) {
            throw $exception;
        } catch (\Exception $e) {
            report($e);
            throw new SystemException('messages.system_error');
        }
    }


    public function removeAlbum(int $albumId, int $userId)
    {
        $currentUser = app('currentUser');
        $albumEntity = $this->_albumRepository->with(['albumLocations.albumLocationMedias'])->find($albumId);
        if ($albumEntity == null) {
            throw new NotFoundException('messages.album_does_not_exist');
        }
        if (!$this->_commonService->allowUpdateAlbum($currentUser, $albumEntity)) {
            throw new ForbiddenException('messages.not_have_permission');
        }
        try {
            $this->beginTransaction();
            $totalSizeMediaAlbum = 0;
            foreach ($albumEntity->albumLocations as $albumLocation) {
                $totalSizeMediaAlbum += $albumLocation->albumLocationMedias->sum('size');
            }
            $userUsage = $albumEntity->user->userUsage;
            $companyUsage = $albumEntity->user->company->companyUsage;
            $albumEntity->delete();
            $this->_dataUsageStatisticService->updateUserUsage($userUsage, [
                'count_album'   =>  ($userUsage->count_album - 1),
                'count_data'    =>  ($userUsage->count_data - $totalSizeMediaAlbum)
            ]);
            $this->_dataUsageStatisticService->updateCompanyUsage($companyUsage, [
                'count_data'    =>  ($companyUsage->count_data - $totalSizeMediaAlbum)
            ]);
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function checkModifyAlbum(int $albumId, string $updatedAt)
    {
        $albumEntity = $this->_albumRepository->find($albumId);
        if ($albumEntity == null) {
            throw new NotFoundException('messages.album_does_not_exist');
        }
        try {
            $status = false;
            $updatedAt = \Illuminate\Support\Carbon::parse($updatedAt);
            if ($updatedAt->lessThan($albumEntity->updated_at)) {
                $status = true;
            }
            return ['modify' => $status];
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function getAlbumLocationTitle(int $albumId)
    {
        $albumEntity = $this->_albumRepository->find($albumId);
        if ($albumEntity == null) {
            throw new NotFoundException('messages.album_does_not_exist');
        }
        $locationTitleOfAlbum = $albumEntity->albumLocations->pluck('title')->values()->toArray();
        $locationTitleDefault = $albumEntity->user->company->locationTypes->pluck('title')->values()->toArray();
        $locationTitles = array_unique(array_merge($locationTitleDefault, $locationTitleOfAlbum));
        $locationTitleResponse = [];
        foreach ($locationTitles as $locationTitle) {
            $locationTitleResponse[] = ['title' => $locationTitle];
        }
        return $locationTitleResponse;
    }
}
