<?php

namespace App\Services;

use App\Exceptions\ForbiddenException;
use App\Models\AlbumModel;
use App\Models\CompanyUsageModel;
use App\Models\UserModel;
use App\Models\UserUsageModel;

class DataUsageStatisticService extends AbstractService
{
    public function updateAlbumSize(AlbumModel $albumModel, int $size)
    {
        $sizeAlbum = $albumModel->size + $size;
        if ($sizeAlbum < 0) {
            $sizeAlbum = 0;
        }
        $albumModel->update([
            'size' => $sizeAlbum
        ]);
    }

    public function updateUserUsage(UserUsageModel $userUsageModel, Array $dataUpdate)
    {
        $userUsageModel->update($dataUpdate);
    }

    public function updateCountDataUserUsage(UserUsageModel $userUsageModel, int $size)
    {
        $sizeUserUsage = $userUsageModel->count_data + $size;
        if ($sizeUserUsage < 0) {
            $sizeUserUsage = 0;
        }
        $this->updateUserUsage($userUsageModel, ['count_data' => $sizeUserUsage]);
    }

    public function updateCompanyUsage(CompanyUsageModel $companyUsageModel, Array $dataUpdate)
    {
        $companyUsageModel->update($dataUpdate);
    }

    public function updateCountDataCompanyUsage(CompanyUsageModel $companyUsageModel, int $size)
    {
        $sizeCompanyUsage = $companyUsageModel->count_data + $size;
        if ($sizeCompanyUsage < 0) {
            $sizeCompanyUsage = 0;
        }
        $this->updateCompanyUsage($companyUsageModel, ['count_data' => $sizeCompanyUsage]);
    }

    public function checkConvertDataUserInCompany(UserModel $userConvert, UserModel $userTarget)
    {
        $userConvertUsage =  $userConvert->userUsage;
        $userTargetUsage =  $userTarget->userUsage;
        if ($userTargetUsage->count_data + $userConvertUsage->count_data > $userTargetUsage->package_data + $userTargetUsage->extend_data + $userConvertUsage->extend_data) {
            throw new ForbiddenException('messages.receiving_account_has_not_enough_storage_space');
        }
    }
}
