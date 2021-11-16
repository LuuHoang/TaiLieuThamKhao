<?php

namespace App\Http\Controllers\AppControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Resources\GuidelineResource;
use App\Services\GuidelineService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class GuidelineController extends Controller
{
    private $_guidelineService;

    public function __construct(GuidelineService $guidelineService)
    {
        $this->_guidelineService = $guidelineService;
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
}
