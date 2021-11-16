<?php

namespace App\Services;

use App\Exceptions\UnauthorizedException;
use App\Models\AdminCompanyInformationModel;
use App\Models\AdminModel;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Hash;

class AuthService extends AbstractService
{
    private $_adminRepository;
    private $_adminCompanyInformationRepository;

    public function __construct(AdminModel $adminModel, AdminCompanyInformationModel $adminCompanyInformationModel)
    {
        $this->_adminRepository = new Repository($adminModel);
        $this->_adminCompanyInformationRepository = new Repository($adminCompanyInformationModel);
    }

    public function loginAdmin(Array $credentials)
    {
        $adminEntity = $this->_adminRepository->where('email', '=', $credentials['email'])->first();

        if ($adminEntity == null || !Hash::check($credentials['password'], $adminEntity->password)) {
            throw new UnauthorizedException('messages.email_or_password_is_incorrect');
        }

        $token =  Hash::make($adminEntity->id . time());
        $adminEntity->adminTokens()->create([
            'token' => $token
        ]);

        $companyData = [];
        $companyInformationEntities = $this->_adminCompanyInformationRepository->all();
        foreach ($companyInformationEntities as $companyInformationEntity) {
            $companyData[$companyInformationEntity->key] = $companyInformationEntity->value;
        }

        return [
            'token' => $token,
            'admin' => $adminEntity,
            'company_data' => $companyData
        ];
    }
}
