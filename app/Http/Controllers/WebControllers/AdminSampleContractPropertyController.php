<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\StampType;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\CreateSampleContractPropertyRequest;
use App\Http\Resources\SampleContractPropertyResource;
use App\Services\WebService\SampleContractPropertyService;
use App\Supports\Facades\Response\Response;

class AdminSampleContractPropertyController extends Controller
{
    private $_sampleContractPropertyService;

    public function __construct(SampleContractPropertyService $companyConfigAlbumService)
    {
        $this->_sampleContractPropertyService = $companyConfigAlbumService;
    }
    public function createSampleContractProperty(CreateSampleContractPropertyRequest $request,int $sampleContractId)
    {
        try {
            $array['title'] = $request->input('title');
            $array['data_type'] = (int)$request->input('data_type');
            $this->_sampleContractPropertyService->createSampleContractProperty($array,$sampleContractId);
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

}
