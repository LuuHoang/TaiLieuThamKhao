<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-04
 * Time: 11:23
 */

namespace App\Http\Controllers\AppControllers;


use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppRequests\DeviceRequest;
use App\Services\AppService\DeviceService;
use Illuminate\Http\Request;
use App\Supports\Facades\Response\Response;

class DeviceController extends Controller
{
    const  DEVICE_KEY = 'getDeviceToken';

    /**
     * @var DeviceService
     */
    private $_deviceService;

    /**
     * DeviceController constructor.
     * @param DeviceService $deviceService
     */
    public function __construct(DeviceService $deviceService)
    {
        $this->_deviceService = $deviceService;
    }

    /**
     * @param DeviceRequest $deviceRequest
     * @return mixed
     * @throws ForbiddenException
     */
    public function register(DeviceRequest $deviceRequest)
    {
        $currentUser = app('currentUser');
        if(empty($currentUser->id)) {
            throw new ForbiddenException('messages.register_device_fail');
        }
        $infoDevice = [
            'user_id' => $currentUser->id,
            'token' => $deviceRequest->input('device_token'),
            'os' => $deviceRequest->input('os')
        ];

        $rs = $this->_deviceService->register($infoDevice);

        if ($rs) {
            return Response::success([
                'message' => 'messages.register_device_success'
            ]);
        }

        throw new ForbiddenException('messages.register_device_fail');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws ForbiddenException
     */
    public function unregister(Request $request)
    {
        $currentUser = app('currentUser');
        if(empty($currentUser->id)) {
            throw new ForbiddenException('messages.unregister_device_fail');
        }

        $rs = $this->_deviceService->unregister($currentUser->id);
        if (rs) {
            return Response::success([
                'message' => 'messages.unregister_device_success'
            ]);
        }

        throw new ForbiddenException('messages.unregister_device_fail');
    }

}