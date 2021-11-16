<?php

namespace App\Services\WebService;

use App\Constants\Disk;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Http\Requests\WebRequests\CreateCompanyRequest;
use App\Repositories\Criteria\SearchCompanyCriteria;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;

class CompanyService extends \App\Services\CompanyService
{
    public function getCompany(int $companyId)
    {
        $companyEntity = $this->_companyRepository->with([
            'servicePackage',
            'extendPackage',
            'companyUsage'
        ])->find($companyId);

        if ($companyEntity == null) {
            throw new NotFoundException('messages.company_id_is_incorrect');
        }
        return $companyEntity;
    }

    public function updateCompany(Array $companyData, int $companyId)
    {
        $currentUser = app('currentUser');
        if ($companyId != $currentUser->company_id) {
            throw new ForbiddenException('messages.company_id_is_incorrect');
        }
        if (empty($companyData)) {
            throw new UnprocessableException('messages.nothing_to_update');
        }
        try {
            $arrayDataUpdate = [];
            $companyEntity = $this->_companyRepository->find($companyId);
            if (!empty($companyData['company_name'])) {
                $arrayDataUpdate['company_name'] = $companyData['company_name'];
            }
            if (!empty($companyData['address'])) {
                $arrayDataUpdate['address'] = $companyData['address'];
            }
            if (!empty($companyData['color'])) {
                $arrayDataUpdate['color'] = $companyData['color'];
            }
            if (!empty($companyData['logo'])) {
                $arrayDataUpdate['logo_path'] = $this->_uploadMediaService->uploadImage($companyData['logo'], Disk::COMPANY);
                $this->_uploadMediaService->deleteMedia($companyEntity->logo_path, Disk::COMPANY);
            }
            $companyEntity->update($arrayDataUpdate);
            return $companyEntity;
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function getCompaniesList(int $limit, Array $paramQuery) : LengthAwarePaginator
    {
        return $this->_companyRepository
            ->pushCriteria(new SearchCompanyCriteria($paramQuery))
            ->with(['companyUsage','contracts'])
            ->paginate($limit);
    }

    public function createCompany(CreateCompanyRequest $request)
    {
        try {
            $companyData = $request->only('company_name', 'company_code', 'address', 'representative', 'tax_code', 'service_id', 'extend_id', 'color', 'logo');
            $logo = Arr::pull($companyData, 'logo');
            $fileName = $this->_uploadMediaService->uploadImage($logo, Disk::COMPANY);
            $companyData['logo_path'] = $fileName;
            if (empty($companyData['company_code'])) {
                $ignore = $this->_companyRepository->all(['company_code'])->pluck('company_code')->toArray();
                $companyData['company_code'] = $this->_randomCompanyCode($ignore);
            }
            return $this->_companyRepository->create($companyData);
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    public function companyCreationAtAddingContract(array $array)
    {
        try {
            $companyData = $array;
            $companyData['color'] =' ';
            $companyData['logo_path'] =' ';
            if (empty($companyData['company_code'])) {
                $ignore = $this->_companyRepository->all(['company_code'])->pluck('company_code')->toArray();
                $companyData['company_code'] = $this->_randomCompanyCode($ignore);
            }
            return $this->_companyRepository->create($companyData);
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    private function _randomCompanyCode(Array $ignore)
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $code = substr(str_shuffle($str_result),  0, 6);
        if (in_array($code, $ignore)) {
            $code = $this->_randomCompanyCode($ignore);
        }
        return $code;
    }

    public function updateCompanyForAdminSCSoft(Array $companyData, int $companyId)
    {
        $companyEntity = $this->_companyRepository->find($companyId);
        if ($companyEntity == null) {
            throw new NotFoundException('messages.company_id_is_incorrect');
        }
        if (empty($companyData)) {
            throw new UnprocessableException('messages.nothing_to_update');
        }
        if (!empty($companyData['service_id'])) {
            $this->_packageService->checkAvailableChangeServicePackage($companyData['service_id'], $companyEntity);
        }
        if (!empty($companyData['extend_id'])) {
            $this->_packageService->checkAvailableChangeExtendPackage($companyData['extend_id'], $companyEntity);
        }
        try {
            $oldLogo = null;
            $logo = Arr::pull($companyData, 'logo');
            if ($logo != null) {
                $fileName = $this->_uploadMediaService->uploadImage($logo, Disk::COMPANY);
                $companyData['logo_path'] = $fileName;
                $oldLogo = $companyEntity->logo_path;
            }
            $this->beginTransaction();
            $companyEntity->update($companyData);
            if (!empty($companyData['service_id'])) {
                $this->_packageService->updatePackageDataOfUserUsage($companyData['service_id'], $companyEntity);
            }
            $this->commitTransaction();
            if ($oldLogo != null) {
                $this->_uploadMediaService->deleteMedia($oldLogo, Disk::COMPANY);
            }
            return $companyEntity;
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function deleteCompany(int $companyId)
    {
        $companyEntity = $this->_companyRepository->find($companyId);
        if ($companyEntity == null) {
            throw new NotFoundException('messages.company_id_is_incorrect');
        }
        try {
            $this->beginTransaction();
            $companyEntity->delete();
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
}
