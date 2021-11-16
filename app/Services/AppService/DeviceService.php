<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-04
 * Time: 11:25
 */

namespace App\Services\AppService;


use App\Models\DeviceModel;
use App\Repositories\Repository;
use mysql_xdevapi\Exception;

class DeviceService extends AbstractService
{

    /**
     * @var DeviceRepository
     */
    protected $_deviceRepository;

    /**
     * DeviceService constructor.
     * @param DeviceRepository $_deviceRepository
     */
    public  function __construct(DeviceModel $deviceModel)
    {
        $this->_deviceRepository = new Repository($deviceModel);
    }

    /**
     * Resiger device
     *
     * @param $data
     *
     * @return bool
     */
    public function register($data)
    {
        try {
            $device = $this->_deviceRepository->where('token', '=', $data['token'])->first();
            if (empty($device)) {
                $device = $this->_deviceRepository->create($data);
            }

            if ($device->user_id != $data['user_id']) {
                $this->_deviceRepository->update($data, $device->id);
            }

            return true;

        } catch (Exception $exception)
        {
            return false;
        }
    }

    /**
     * Remove device token
     *
     * @param int $userId
     * @param int $exceptDeviceId
     *
     * @return bool
     */
    public function unregister($userId)
    {
        try {
            $this->_deviceRepository->where('user_id', '=', $userId)->delete();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}