<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\CreateAppVersionRequest;
use App\Http\Requests\WebRequests\UpdateAppVersionRequest;
use App\Http\Resources\AppVersionResource;
use App\Services\WebService\AppVersionService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
    protected $appVersionService;

    public function __construct(AppVersionService $appVersionService)
    {
        $this->appVersionService = $appVersionService;
    }

    public function retrieveListAppVersions(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $queryParam = $request->all(['search']);
        $result = $this->appVersionService->retrieveListAppVersions($queryParam, $limit);
        return Response::pagination(
            AppVersionResource::collection($result),
            $result->total(),
            $result->currentPage(),
            $limit
        );
    }

    public function retrieveAppVersionDetail(int $versionId)
    {
        $result = $this->appVersionService->retrieveAppVersionDetail($versionId);
        return Response::success([
            "version" => new AppVersionResource($result)
        ]);
    }

    public function createAppVersion(CreateAppVersionRequest $request)
    {
        $versionData = $request->all(['name', 'en_description', 'active', 'version_ios', 'version_android', 'ja_description', 'vi_description']);
        $this->appVersionService->createAppVersion($versionData);
        return Response::success();
    }

    public function updateAppVersion(UpdateAppVersionRequest $request, int $versionId)
    {
        $versionData = $request->only(['name', 'en_description', 'active', 'version_ios', 'version_android', 'ja_description', 'vi_description']);
        $this->appVersionService->updateAppVersion($versionData, $versionId);
        return Response::success();
    }
}
