<?php


namespace App\Services\AppService;


use App\Constants\Permission;
use App\Constants\Platform;
use App\Exceptions\AuthenticationDenied;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\Hash;

class UserService extends \App\Services\UserService
{
    public function login(array $credentials)
    {
        $companyEntity = $this->_companyRepository
            ->where('company_code', '=', $credentials['company_code'])
            ->first();

        if ($companyEntity == null)
            throw new NotFoundException('messages.company_code_is_incorrect');

        $userEntity = $this->_userRepository
            ->where('company_id', '=', $companyEntity->id)
            ->where('email', '=', $credentials['email'])
            ->first();

        if ($userEntity == null || !Hash::check($credentials['password'], $userEntity->password)) {
            throw new UnauthorizedException('messages.email_or_password_is_incorrect');
        }

        if (!$this->_commonService->checkPermission(json_decode($userEntity->userRole->permissions ?? '[]', true), Permission::LOGIN, Platform::APP)) {
            throw new AuthenticationDenied('messages.can_not_login_to_this_platform');
        }

        if (!empty($credentials['device_token'])) {
            $this->_userTokenRepository->delete([['device_token', '=', $credentials['device_token']]]);
        }

        $token = Hash::make($userEntity->id . now()->timestamp);
        $userEntity->userTokens()->create([
            'token'         => $token,
            'os'            => $credentials['os'] ?? null,
            'device_token'  => $credentials['device_token'] ?? null
        ]);

        return [
            'token' => $token,
            'user' => $userEntity
        ];
    }
}
