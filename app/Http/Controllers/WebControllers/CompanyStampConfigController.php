<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\StampType;
use App\Http\Requests\WebRequests\CreateCompanyConfigAlbumRequest;
use App\Http\Resources\CompanyStampConfigResource;
use App\Services\WebService\CompanyConfigAlbumService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CompanyStampConfigController extends Controller
{
    private $_companyConfigAlbumService;

    public function __construct(CompanyConfigAlbumService $companyConfigAlbumService)
    {
        $this->_companyConfigAlbumService = $companyConfigAlbumService;
    }
    public function updateCompanyConfigStampAlbum(CreateCompanyConfigAlbumRequest $request)
    {
        $currentUser = app('currentUser');
        $array['stamp_type'] = (int)$request->input('stamp_type');
        $array['mounting_position'] = (int)$request->input('mounting_position');
        if($array['stamp_type'] === StampType::ICON){
                $array['image'] = $request->file('icon');
        }
        if($array['stamp_type'] === StampType::TEXT){
            $array['text']=$request->input('text');
        }
        $config = $this->_companyConfigAlbumService->updateCompanyConfigStampAlbum($array,$currentUser->company_id);
        return Response::success([
            'album_config_company'  =>  new CompanyStampConfigResource($config)
        ]);
    }
    public  function  getStampConfig()
    {
        $companyId = app('currentUser')->company_id;
        $config = $this->_companyConfigAlbumService->getStampConfig($companyId);
        return Response::success([
            'album_config_company'  =>  new CompanyStampConfigResource($config)
        ]);
    }

}
