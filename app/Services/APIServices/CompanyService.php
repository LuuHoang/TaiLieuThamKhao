<?php
namespace App\Services\APIServices;

use App\Exceptions\NotFoundException;
use App\Models\CompanyModel;
use App\Models\LocationTypeModel;
use App\Models\UserModel;
use App\Models\AlbumPropertyModel;
use App\Models\CompanyUsageModel;
use App\Repositories\Repository;
use App\Services\AbstractService;

class CompanyService extends AbstractService
{
    /**
     * @var Repository
     */
    protected $_companyRepository;

    /**
     * @var Repository
     */
    protected $_locationTypesRepository;

    /**
     * @var Repository
     */
    protected $_usersRepository;

    /**
     * @var Repository
     */
    protected $_albumPropertiesRepository;

    /**
     * @var Repository
     */
    protected $_companyUsageRepository;

    public function __construct(
        CompanyModel $companyModel,
        LocationTypeModel $locationTypeModel,
        UserModel $userModel,
        AlbumPropertyModel $albumPropertyModel,
        CompanyUsageModel $companyUsageModel
    ) {
        $this->_companyRepository = new Repository($companyModel);
        $this->_locationTypesRepository = new Repository($locationTypeModel);
        $this->_usersRepository = new Repository($userModel);
        $this->_albumPropertiesRepository = new Repository($albumPropertyModel);
        $this->_companyUsageRepository = new Repository($companyUsageModel);
    }

    public function getCompanyByCompanyCode(string $companyCode)
    {
        $companyEntity = $this->_companyRepository->where('company_code', '=', $companyCode)->first();

        if($companyEntity == null) {
            throw new NotFoundException('messages.company_code_is_incorrect');
        }

        return $companyEntity;
    }
}
