<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-04
 * Time: 14:14
 */

namespace App\Http\Controllers\AppControllers;

use App\Constants\App;
use App\Constants\CommentCreator;
use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppRequests\CreateAlbumLocationCommentRequest;
use App\Http\Requests\AppRequests\CreateAlbumLocationMediaCommentRequest;
use App\Http\Requests\AppRequests\UpdateAlbumLocationCommentRequest;
use App\Http\Requests\AppRequests\UpdateAlbumLocationMediaCommentRequest;
use App\Http\Resources\AlbumLocationCommentResource;
use App\Http\Resources\AlbumLocationMediaCommentResource;
use App\Services\AppService\CommentService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @var CommentService
     */
    protected $commentService;

    /**
     * CommentController constructor.
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @param Request $request
     * @param int $albumId
     * @param int $locationId
     * @return \Illuminate\Http\JsonResponse
     * @throws ForbiddenException
     * @throws \App\Exceptions\NotFoundException
     */
    public function getListLocationComment(Request $request, int $albumId, int $locationId)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $params = $request->only(['after_time']);

        $result = $this->commentService->getListLocationComment($albumId, $locationId, $limit, $params);

        return Response::pagination(
            AlbumLocationCommentResource::collection($result->reverse()->values()),
            $result->total(),
            $result->currentPage(),
            $limit
        );
    }

    /**
     * @param Request $request
     * @param int $albumId
     * @param int $locationId
     * @return \Illuminate\Http\JsonResponse
     * @throws ForbiddenException
     * @throws \App\Exceptions\NotFoundException
     */
    public function getListNewLocationComment(Request $request, int $albumId, int $locationId)
    {
        $params = $request->only(['after_time']);

        $result = $this->commentService->getListNewLocationComment($albumId, $locationId, $params);

        return Response::success([
            'data_list' =>  AlbumLocationCommentResource::collection($result->reverse()->values())
        ]);
    }

    /**
     * @param CreateAlbumLocationCommentRequest $request
     * @param int $albumId
     * @param int $locationId
     * @return \Illuminate\Http\JsonResponse
     * @throws ForbiddenException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\SystemException
     */
    public function addLocationComment(CreateAlbumLocationCommentRequest $request, int $albumId, int $locationId)
    {
        $param = $request->only('content');
        $param['creator_type'] = CommentCreator::USER;

        $result = $this->commentService->addLocationComment($param, $albumId, $locationId);

        return Response::success([
            'album_location_comment' => new AlbumLocationCommentResource($result),
        ]);
    }

    /**
     * @param UpdateAlbumLocationCommentRequest $request
     * @param int $locationId
     * @param int $commentId
     * @return \Illuminate\Http\JsonResponse
     * @throws ForbiddenException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\SystemException
     */
    public function editLocationComment(UpdateAlbumLocationCommentRequest $request, int $locationId, int $commentId)
    {
        $param = $request->only('content');
        $param['creator_type'] = CommentCreator::USER;

        $result = $this->commentService->editLocationComment($param, $locationId, $commentId);

        return Response::success([
            'album_location_comment' => new AlbumLocationCommentResource($result),
        ]);
    }

    /**
     * @param Request $request
     * @param int $locationId
     * @param int $mediaId
     * @return \Illuminate\Http\JsonResponse
     * @throws ForbiddenException
     * @throws \App\Exceptions\NotFoundException
     */
    public function getListMediaComment(Request $request, int $locationId, int $mediaId)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $params = $request->only(['after_time']);

        $result = $this->commentService->getListMediaComment($locationId, $mediaId, $limit, $params);

        return Response::pagination(
            AlbumLocationMediaCommentResource::collection($result->reverse()->values()),
            $result->total(),
            $result->currentPage(),
            $limit
        );
    }

    /**
     * @param Request $request
     * @param int $locationId
     * @param int $mediaId
     * @return \Illuminate\Http\JsonResponse
     * @throws ForbiddenException
     * @throws \App\Exceptions\NotFoundException
     */
    public function getListNewMediaComment(Request $request, int $locationId, int $mediaId)
    {
        $params = $request->only(['after_time']);

        $result = $this->commentService->getListNewMediaComment($locationId, $mediaId, $params);

        return Response::success([
            'data_list' =>  AlbumLocationMediaCommentResource::collection($result->reverse()->values())
        ]);
    }

    /**
     * @param CreateAlbumLocationMediaCommentRequest $request
     * @param int $locationId
     * @param int $mediaId
     * @return \Illuminate\Http\JsonResponse
     * @throws ForbiddenException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\SystemException
     */
    public function addMediaComment(CreateAlbumLocationMediaCommentRequest $request, int $locationId, int $mediaId)
    {
        $param = $request->only('content');
        $param['creator_type'] = CommentCreator::USER;

        $result = $this->commentService->addMediaComment($param, $locationId, $mediaId);

        return Response::success([
            'location_media_comment' => new AlbumLocationMediaCommentResource($result)
        ]);
    }

    /**
     * @param UpdateAlbumLocationMediaCommentRequest $request
     * @param int $mediaId
     * @param int $commentId
     * @return \Illuminate\Http\JsonResponse
     * @throws ForbiddenException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\SystemException
     */
    public function editMediaComment(UpdateAlbumLocationMediaCommentRequest $request, int $mediaId, int $commentId)
    {
        $param = $request->only('content');
        $param['creator_type'] = CommentCreator::USER;

        $result = $this->commentService->editMediaComment($param, $mediaId, $commentId);

        return Response::success([
            'location_media_comment' => new AlbumLocationMediaCommentResource($result)
        ]);
    }
}
