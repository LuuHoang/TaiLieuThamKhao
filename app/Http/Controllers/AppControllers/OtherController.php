<?php

namespace App\Http\Controllers\AppControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\CreateOrUpdateLinkVersionRequest;
use App\Http\Resources\AppVersionResource;
use App\Http\Resources\LinkVersionDetailResource;
use App\Services\AppService\LinkVersionService;
use App\Services\OtherService;
use App\Supports\Facades\Response\Response;

class OtherController extends Controller
{
    private $_otherService;
    private $_linkVersionService;

    public function __construct(OtherService $otherService, LinkVersionService $linkVersionService)
    {
        $this->_otherService = $otherService;
        $this->_linkVersionService = $linkVersionService;
    }

    public function getDepartments()
    {
        $departments = $this->_otherService->getDepartments();
        return Response::success([
            "departments"   =>  $departments
        ]);
    }

    public function getPositions()
    {
        $positions = $this->_otherService->getPositions();
        return Response::success([
            "positions"   =>  $positions
        ]);
    }

    public function retrieveListAppVersions()
    {
        $result = $this->_linkVersionService->getLinkVersion();
        $versions = $this->_otherService->retrieveListAppVersions();
        return Response::success([
            "versions"   =>  $versions,
            'links' => new LinkVersionDetailResource($result)
        ]);
    }

    public function getLinkVersion()
    {
        $result = $this->_linkVersionService->getLinkVersion();
        return Response::success([
            'links' => new LinkVersionDetailResource($result)
        ]);
    }

    public function getVersionActive()
    {
        $result = $this->_linkVersionService->getLinkVersion();
        $versions = $this->_otherService->retrieveListAppVersion();
        return Response::success([
            "version" => new AppVersionResource($versions),
            'links' => new LinkVersionDetailResource($result)

        ]);
    }
}
