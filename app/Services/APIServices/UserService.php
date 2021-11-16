<?php

namespace App\Services\APIServices;

use App\Constants\App;
use App\Constants\Device;
use App\Constants\Permission;
use App\Constants\Platform;
use App\Exceptions\AuthenticationDenied;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UnprocessableException;
use App\Mail\UserForgotPassword;
use App\Models\CompanyModel;
use App\Models\UserForgotPasswordModel;
use App\Models\UserModel;
use App\Models\UserTokenModel;
use App\Repositories\Repository;
use App\Services\AbstractService;
use App\Services\CommonService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService extends AbstractService
{
    private $_userRepository;
    private $_companyRepository;
    private $_userTokenRepository;
    private $_userForgotPasswordRepository;
    private $_commonService;

    public function __construct(
        UserModel $userModel,
        CompanyModel $companyModel,
        UserTokenModel $userTokenModel,
        UserForgotPasswordModel $userForgotPasswordModel,
        CommonService $commonService
    )
    {
        $this->_userRepository = new Repository($userModel);
        $this->_companyRepository = new Repository($companyModel);
        $this->_userTokenRepository = new Repository($userTokenModel);
        $this->_userForgotPasswordRepository = new Repository($userForgotPasswordModel);
        $this->_commonService = $commonService;
    }

    public function getUserByToken(string $token)
    {
        $userTokenEntity = $this->_userTokenRepository
            ->where('token', '=', $token)
            ->first();
        if ($userTokenEntity == null) {
            throw new UnauthorizedException('messages.session_has_expired');
        }
        $userEntity = $userTokenEntity->user;
        $platform = Platform::APP;
        if ($userTokenEntity->os === Device::WEB) {
            $platform = Platform::WEB;
        }
        if (!$this->_commonService->checkPermission(json_decode($userEntity->userRole->permissions ?? '[]', true), Permission::LOGIN, $platform)) {
            throw new AuthenticationDenied('messages.can_not_login_to_this_platform');
        }
        return $userEntity;
    }

    public function sendForgetPasswordEmail(array $dataUser)
    {
        $userEntities = $this->_userRepository
            ->with(['company'])
            ->where('email', '=', $dataUser['email'])->all();
        if ($userEntities->isEmpty()) {
            throw new NotFoundException('messages.email_does_not_exist');
        }
        $targetUserEntity = null;
        foreach ($userEntities as $userEntity) {
            if ($userEntity->company->company_code == $dataUser['company_code']) {
                $targetUserEntity = $userEntity;
                break;
            }
        }
        if ($targetUserEntity == null) {
            throw new NotFoundException('messages.user_does_not_exist');
        }
        try {
            $this->beginTransaction();
            $otp_code = $this->_randomOTPCodes();

            $targetUserEntity->userForgotPassword()->delete();
            $targetUserEntity->userForgotPassword()->create([
                'otp_code' => $otp_code,
                'expire_time' => now()->addMinute(App::EXPIRE_TIME)
            ]);

            Mail::to($targetUserEntity->email)->send(new UserForgotPassword($otp_code));

            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    private function _randomOTPCodes()
    {

        // String of all numeric character
        $str_result = '0123456789';

        // Shuffle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result), 0, 6);
    }

    public function resetPassword(array $data)
    {
        $forgotPasswordEntity = $this->_userForgotPasswordRepository
            ->where('otp_code', '=', $data['otp_code'])->first();
        if ($forgotPasswordEntity == null) {
            throw new NotFoundException('messages.otp_code_is_incorrect');
        }
        if (strtotime($forgotPasswordEntity->expire_time) < time()) {
            throw new ForbiddenException('messages.otp_code_has_expired');
        }
        try {
            $this->beginTransaction();
            $forgotPasswordEntity->user()->update([
                'password' => Hash::make($data['password']),
            ]);
            $forgotPasswordEntity->user->userTokens()->delete();
            $forgotPasswordEntity->delete();
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function changePassword(UserModel $user, array $data, string $token)
    {
        if ($data['old_password'] == $data['password']) {
            throw new UnprocessableException('messages.new_password_must_different_previous_password');
        }

        if (!Hash::check($data['old_password'], $user->password)) {
            throw new UnprocessableException('messages.old_password_is_incorrect');
        }

        try {
            $this->beginTransaction();
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
            $this->_userTokenRepository
                ->delete([
                    ['user_id', '=', $user->id],
                    ['token', '<>', $token]
                ]);
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function logout(string $bearerToken)
    {
        $userToken = $this->_userTokenRepository->where('token', '=', $bearerToken)->first();
        if (!empty($userToken->device_token)) {
            $this->_userTokenRepository->delete([['token', '<>', $bearerToken], ['device_token', '=', $userToken->device_token]]);
        }
        $userToken->delete();
    }

    public function updateDeviceToken(string $bearerToken, array $params)
    {
        if (!empty($params['device_token'])) {
            $this->_userTokenRepository->delete([['token', '<>', $bearerToken], ['device_token', '=', $params['device_token']]]);
        }
        $this->_userTokenRepository->update([['token', '=', $bearerToken]], $params);
    }
}
