<?php

namespace App\Services\WebService;

use App\Constants\Boolean;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Models\AppVersionModel;
use App\Repositories\Criteria\SearchAppVersionsCriteria;
use App\Repositories\Repository;
use App\Services\AbstractService;

class AppVersionService extends AbstractService
{
    protected $appVersionRepository;

    public function __construct(AppVersionModel $appVersionModel)
    {
        $this->appVersionRepository = new Repository($appVersionModel);
    }

    public function retrieveListAppVersions(array $queryParam, int $limit)
    {
        try {
            return $this->appVersionRepository
                ->pushCriteria(new SearchAppVersionsCriteria($queryParam))
                ->orderBy('created_at', 'desc')
                ->paginate($limit);
        } catch (\Exception $e) {
            report($e);
            throw new SystemException('messages.system_error');
        }
    }

    public function retrieveAppVersionDetail(int $versionId)
    {
        $versionEntity = $this->appVersionRepository->find($versionId);
        if (!$versionEntity) {
            throw new NotFoundException('messages.version_does_not_exist');
        }
        return $versionEntity;
    }

    public function createAppVersion(array $data)
    {
        try {
            $this->beginTransaction();
            $versionActiveEntity = $this->appVersionRepository->where('active', '=', Boolean::TRUE)->first();
            if (!$versionActiveEntity) {
                $data['active'] = Boolean::TRUE;
            }
            if ($versionActiveEntity && $data['active']) {
                $versionActiveEntity->update(['active' => Boolean::FALSE]);
            }
            $this->appVersionRepository->create($data);
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('errors.system_error');
        }
    }

    public function updateAppVersion(array $data, int $versionId)
    {
        $versionEntity = $this->appVersionRepository->find($versionId);
        if (!$versionEntity) {
            throw new NotFoundException('messages.version_does_not_exist');
        }
        try {
            $this->beginTransaction();
            $versionActiveEntity = $this->appVersionRepository->where('active', '=', Boolean::TRUE)->first();
            if (!$versionActiveEntity || $versionEntity->id === $versionActiveEntity->id) {
                $data['active'] = Boolean::TRUE;
            }
            if ($versionActiveEntity && $versionEntity->id !== $versionActiveEntity->id && $data['active']) {
                $versionActiveEntity->update(['active' => Boolean::FALSE]);
            }
            $versionEntity->update($data);
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('errors.system_error');
        }
    }
}
