<?php
namespace App\Services\APIServices;

use App\Services\AbstractService;
use App\Models\UserSettingModel;
use App\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserSettingService extends AbstractService
{

    /**
     * @var Repository
     */
    protected $_userSettingRepository;

    public function __construct(
        UserSettingModel $userSettingModel
    ) {
        $this->_userSettingRepository = new Repository($userSettingModel);
    }

    public function updateSetting(Request $request, $userId)
    {
        try {
            $userSettingEntity = $this->_userSettingRepository->where('user_id', '=', $userId)->first();
            if (!$userSettingEntity) {
                $data = $request->all([
                    'image_size',
                    'language',
                    'voice',
                    'comment_display'
                ]);
                $this->beginTransaction();
                $this->_userSettingRepository->create([
                    'image_size'     => $data['image_size'],
                    'language'       => strtolower($data['language']),
                    'voice'          => $data['voice'],
                    'comment_display' => $data['comment_display'],
                    'user_id'        => $userId,
                ]);

                $this->commitTransaction();
                return true;
            }
            $data = $request->all([
                'image_size',
                'language',
                'voice',
                'comment_display'
            ]);
            $this->beginTransaction();
            $userSettingEntity->update($data);
            $result = $this->getUserSetting($userId);
            $this->commitTransaction();
            return $result;
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            report($e);
            return null;
        }
    }

    public function getUserSetting($userId) {
        $userSettingEntity = $this->_userSettingRepository->where(
            'user_id', '=', $userId
            )->first(['image_size','language','voice', 'comment_display']);
        if (!$userSettingEntity) {
            abort(JsonResponse::HTTP_NOT_FOUND);
        }

        return $userSettingEntity;
    }

}
