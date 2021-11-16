<?php

namespace App\Services;

use App\Models\CompanyModel;
use App\Models\UserUsageModel;
use App\Repositories\Repository;

class CompanyService extends AbstractService
{
    protected $_companyRepository;
    protected $_userUsageRepository;
    protected $_uploadMediaService;
    protected $_packageService;

    public function __construct (
        CompanyModel $companyModel,
        UserUsageModel $userUsageModel,
        UploadMediaService $uploadMediaService,
        PackageService $packageService
    )
    {
        $this->_companyRepository = new Repository($companyModel);
        $this->_userUsageRepository = new Repository($userUsageModel);
        $this->_uploadMediaService = $uploadMediaService;
        $this->_packageService = $packageService;
    }
}
