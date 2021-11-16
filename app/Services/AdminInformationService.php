<?php


namespace App\Services;
use App\Models\AdminInformationModel;
use App\Repositories\Repository;

class AdminInformationService extends AbstractService
{
    private $_adminInformationRepository;
    public function __construct(
        AdminInformationModel  $adminInformationModel

    )
    {
        $this->_adminInformationRepository = new Repository($adminInformationModel);
    }
    public function getListShortCode()
    {
        $shortCode=$this->_adminInformationRepository->all(['company_name','address','phone']);
        return $shortCode;
    }
}
