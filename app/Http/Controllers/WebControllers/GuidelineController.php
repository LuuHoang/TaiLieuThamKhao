<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\CreateGuidelineRequest;
use App\Http\Requests\WebRequests\UpdateGuidelineRequest;
use App\Http\Resources\GuidelineResource;
use App\Services\WebService\GuidelineService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class GuidelineController extends Controller
{
    private $_guidelineService;

    public function __construct(GuidelineService $guidelineService)
    {
        $this->_guidelineService = $guidelineService;
    }

    public function createGuideline(CreateGuidelineRequest $request)
    {
        $params = $request->only(['title', 'content', 'information']);
        $result = $this->_guidelineService->createGuideline($params);
        return Response::success([
            "guideline" => new GuidelineResource($result)
        ]);
    }

    public function getListGuidelines(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $params = $request->only(['search']);
        $result = $this->_guidelineService->getListGuidelines($params, $limit);
        return Response::pagination(
            GuidelineResource::collection($result),
            $result->total(),
            $result->currentPage(),
            $limit
        );
    }

    public function getGuideline(int $guidelineId)
    {
        $result = $this->_guidelineService->getGuideline($guidelineId);
        return Response::success([
            "guideline" => new GuidelineResource($result)
        ]);
    }

    public function updateGuideline(UpdateGuidelineRequest $request, int $guidelineId)
    {
        $bodyData = $request->only(['title', 'content', 'information']);
        $result = $this->_guidelineService->updateGuideline($guidelineId, $bodyData);
        return Response::success([
            "guideline" => new GuidelineResource($result)
        ]);
    }

    public function deleteGuideline(int $guidelineId)
    {
        $this->_guidelineService->deleteGuideline($guidelineId);
        return Response::success();
    }

    public function deleteGuidelineInformation(int $guidelineId, int $informationId)
    {
        $this->_guidelineService->deleteGuidelineInformation($guidelineId, $informationId);
        return Response::success();
    }

    public function deleteGuidelineInformationMedia(int $guidelineId, int $informationId, int $mediaId)
    {
        $this->_guidelineService->deleteGuidelineInformationMedia($guidelineId, $informationId, $mediaId);
        return Response::success();
    }
}
