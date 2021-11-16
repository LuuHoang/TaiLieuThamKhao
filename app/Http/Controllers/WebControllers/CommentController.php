<?php
/**
 * Created by PhpStorm.
 * User: thuydt
 * Date: 2020-08-04
 * Time: 14:14
 */

namespace App\Http\Controllers\WebControllers;

use App\Constants\App;
use App\Constants\CommentCreator;
use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\CreateAlbumLocationCommentRequest;
use App\Http\Requests\WebRequests\CreateAlbumLocationMediaCommentRequest;
use App\Http\Requests\WebRequests\CreateShareAlbumLocationCommentRequest;
use App\Http\Requests\WebRequests\CreateShareAlbumLocationMediaCommentRequest;
use App\Http\Requests\WebRequests\ShareUserGetListCommentRequest;
use App\Http\Requests\WebRequests\UpdateAlbumLocationCommentRequest;
use App\Http\Requests\WebRequests\UpdateAlbumLocationMediaCommentRequest;
use App\Http\Requests\WebRequests\UpdateShareAlbumLocationCommentRequest;
use App\Http\Requests\WebRequests\UpdateShareAlbumLocationMediaCommentRequest;
use App\Http\Resources\AlbumLocationCommentResource;
use App\Http\Resources\AlbumLocationMediaCommentResource;
use App\Services\WebService\CommentService;
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

        $result = $this->commentService->getListLocationComment($albumId, $locationId, $limit);

        return Response::pagination(
            AlbumLocationCommentResource::collection($result->reverse()->values()),
            $result->total(),
            $result->currentPage(),
            $limit
        );
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

        $result = $this->commentService->getListMediaComment($locationId, $mediaId, $limit);

        return Response::pagination(
            AlbumLocationMediaCommentResource::collection($result->reverse()->values()),
            $result->total(),
            $result->currentPage(),
            $limit
        );
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

    public function getListShareAlbumLocationComment(ShareUserGetListCommentRequest $request, int $albumId, int $locationId)
    {
        $param = $request->only('token', 'password');
        $limit = $request->query('limit', App::PER_PAGE);
        $result = $this->commentService->getListShareAlbumLocationComment($param, $albumId, $locationId, $limit);

        return Response::pagination(
            AlbumLocationCommentResource::collection($result->reverse()->values()),
            $result->total(),
            $result->currentPage(),
            $limit
        );
    }

    public function createShareAlbumLocationComment(CreateShareAlbumLocationCommentRequest $request, int $albumId, int $locationId)
    {
        $param = $request->only('token', 'password', 'content');
        $param['creator_type'] = CommentCreator::SHARE_USER;
        $result = $this->commentService->createShareAlbumLocationComment($param, $albumId, $locationId);

        return Response::success([
            'album_location_comment' => new AlbumLocationCommentResource($result),
        ]);
    }

    public function editShareAlbumLocationComment(UpdateShareAlbumLocationCommentRequest $request, int $locationId, int $commentId)
    {
        $param = $request->only('token', 'password', 'content');
        $param['creator_type'] = CommentCreator::SHARE_USER;
        $result = $this->commentService->editShareAlbumLocationComment($param, $locationId, $commentId);

        return Response::success([
            'album_location_comment' => new AlbumLocationCommentResource($result),
        ]);
    }

    public function getListShareAlbumLocationMediaComment(ShareUserGetListCommentRequest $request, int $locationId, int $mediaId)
    {
        $param = $request->only('token', 'password');
        $limit = $request->query('limit', App::PER_PAGE);

        $result = $this->commentService->getListShareAlbumLocationMediaComment($param, $locationId, $mediaId, $limit);

        return Response::pagination(
            AlbumLocationMediaCommentResource::collection($result->reverse()->values()),
            $result->total(),
            $result->currentPage(),
            $limit
        );
    }

    public function createShareAlbumLocationMediaComment(CreateShareAlbumLocationMediaCommentRequest $request, int $locationId, int $mediaId)
    {
        $param = $request->only('token', 'password', 'content');
        $param['creator_type'] = CommentCreator::SHARE_USER;

        $result = $this->commentService->createShareAlbumLocationMediaComment($param, $locationId, $mediaId);

        return Response::success([
            'location_media_comment' => new AlbumLocationMediaCommentResource($result)
        ]);
    }

    public function editShareAlbumLocationMediaComment(UpdateShareAlbumLocationMediaCommentRequest $request, int $mediaId, int $commentId)
    {
        $param = $request->only('token', 'password', 'content');
        $param['creator_type'] = CommentCreator::SHARE_USER;

        $result = $this->commentService->editShareAlbumLocationMediaComment($param, $mediaId, $commentId);

        return Response::success([
            'location_media_comment' => new AlbumLocationMediaCommentResource($result)
        ]);
    }
}
