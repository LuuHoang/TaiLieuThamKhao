<?php

namespace App\Services;

use App\Constants\Disk;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UnprocessableException;
use App\Models\AdminCompanyInformationModel;
use App\Models\AdminModel;
use App\Models\AdminTokenModel;
use App\Models\ContractModel;
use App\Repositories\Criteria\SearchAdminCriteria;
use App\Repositories\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminService extends AbstractService
{
    private $_adminRepository;
    private $_adminTokenRepository;
    private $_adminCompanyInformationRepository;

    public function __construct(
        AdminModel $adminModel,
        AdminTokenModel $adminTokenModel,
        AdminCompanyInformationModel $adminCompanyInformationModel
    )
    {
        $this->_adminRepository = new Repository($adminModel);
        $this->_adminTokenRepository = new Repository($adminTokenModel);
        $this->_adminCompanyInformationRepository = new Repository($adminCompanyInformationModel);
    }

    public function getCompanyData()
    {
        $companyData = [];
        $companyInformationEntities = $this->_adminCompanyInformationRepository->all();
        foreach ($companyInformationEntities as $companyInformationEntity) {
            $companyData[$companyInformationEntity->key] = $companyInformationEntity->value;
        }
        return $companyData;
    }

    public function getAdminByToken(string $token)
    {
        $adminTokenEntity = $this->_adminTokenRepository
            ->where('token', '=', $token)
            ->first();
        if ($adminTokenEntity == null) {
            throw new UnauthorizedException('messages.session_has_expired');
        }

        return $adminTokenEntity->admin;
    }

    public function logout(string $bearerToken)
    {
        $this->_adminTokenRepository
            ->delete([
                ['token', '=', $bearerToken]
            ]);
    }
    public function getListUserAreAdmin()
    {
        try{
            $userInformationEntities = $this->_adminRepository->all(['id','full_name']);
            return $userInformationEntities;
        }catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    public function createAdmin(array $dataAdmin){
        try {
            $currentAdmin = app('currentAdmin');
            $this->beginTransaction();
            $dataAdmin['password'] = Hash::make($dataAdmin['password']);
            if(array_key_exists('avatar_path',$dataAdmin) && $dataAdmin['avatar_path'] && $dataAdmin['avatar_path'] instanceof UploadedFile) {
                $fileName = $currentAdmin->id . '/' . time() . $currentAdmin->id . '.' . $dataAdmin['avatar_path']->extension();
                Storage::disk(Disk::ADMIN)->put($fileName, file_get_contents($dataAdmin['avatar_path']));
                $dataAdmin['avatar_path'] = $fileName;
            }
            $newAdmin = $this->_adminRepository->create($dataAdmin);
            $this->commitTransaction();
            return $newAdmin;
        }
        catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    public function updateAdmin(array $dataAdmin , int $adminId)
    {
        try {
            $currentAdmin = app('currentAdmin');
            $admin = $this->_adminRepository->find($adminId);
            if(!$admin){
                throw new NotFoundException('messages.not_found_admin_with_admin_id');
            }
            if(isset($dataAdmin['password']))
            {
                $dataAdmin['password'] = Hash::make($dataAdmin['password']);
            }
            if(!$dataAdmin['avatar_path'])
            {
                unset($dataAdmin['avatar_path']);
            }
            $this->beginTransaction();
            if(array_key_exists('avatar_path',$dataAdmin) && $dataAdmin['avatar_path'] && $dataAdmin['avatar_path'] instanceof UploadedFile) {
                $avatar_path = $admin->avatar_path;
                Storage::disk(Disk::ADMIN)->delete($avatar_path);
                $fileName = $currentAdmin->id . '/' . time() . $currentAdmin->id . '.' . $dataAdmin['avatar_path']->extension();
                Storage::disk(Disk::ADMIN)->put($fileName, file_get_contents($dataAdmin['avatar_path']));
                $dataAdmin['avatar_path'] = $fileName;
            }
            $admin->update($dataAdmin);
            $this->commitTransaction();
            return $admin;
        }catch (NotFoundException $notFoundException)
        {
            report($notFoundException);
            throw new SystemException('messages.not_found_admin_with_admin_id');
        }
        catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    public function deleteAdmin(int $adminId,int $responsibleAdminId)
    {
        try {
            $admin = $this->_adminRepository->find($adminId);
            if(!$admin){
                throw new NotFoundException('messages.not_found_admin_with_admin_id');
            }
            $responsibleAdmin = $this->_adminRepository->find($responsibleAdminId);
            if(!$responsibleAdmin){
                throw new NotFoundException('messages.not_found_$responsible_Admin_with_responsible_admin_id');
            }
            $contracts = ContractModel::where('employee_represent','=',$adminId)->get();
            foreach ($contracts as $contract)
            {
                $contract->employee_represent = $responsibleAdminId;
                $contract->save();
            }
            return null;
        }catch (NotFoundException $notFoundException)
        {
            report($notFoundException);
            throw new SystemException('messages.not_found_admin_with_admin_id');
        }
        catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function getListAdmin(int $limit, Array $paramQuery): LengthAwarePaginator
    {
        return $this->_adminRepository
            ->pushCriteria(new SearchAdminCriteria($paramQuery))
            ->paginate($limit);
    }
}
