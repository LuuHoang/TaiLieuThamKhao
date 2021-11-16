<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\CreateAlbumPDFFormatRequest;
use App\Http\Requests\WebRequests\UpdateAlbumPDFFormatRequest;
use App\Http\Resources\AlbumPDFFormatResource;
use App\Http\Resources\ListAlbumPDFFormatResource;
use App\Services\WebService\AlbumPDFService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\Request;

class AlbumPDFController extends Controller
{
    private $_albumPDFService;

    public function __construct(AlbumPDFService $albumPDFService)
    {
        $this->_albumPDFService = $albumPDFService;
    }

    public function getListAlbumPDFFormats(Request $request)
    {
        $limit = $request->query('limit', App::PER_PAGE);
        $paramQuery = $request->only(['search', 'sort', 'filter']);
        $result = $this->_albumPDFService->getListAlbumPDFFormats($limit, $paramQuery);
        return Response::pagination(
            ListAlbumPDFFormatResource::collection($result),
            $result->total(),
            $result->currentPage(),
            $limit
        );
    }

    public function createAlbumPDFFormat(CreateAlbumPDFFormatRequest $request)
    {
        $params = $request->only(['title', 'description', 'album_type_id', 'cover_page', 'content_page', 'last_page', 'number_images', 'content_page_id']);
        $result = $this->_albumPDFService->createAlbumPDFFormat($params);
        return Response::success([
            "album_pdf_format"  => new AlbumPDFFormatResource($result)
        ]);
    }

    public function getAlbumPDFFormat(int $formatId)
    {
        $result = $this->_albumPDFService->getAlbumPDFFormat($formatId);
        return Response::success([
            "album_pdf_format"  => new AlbumPDFFormatResource($result)
        ]);
    }

    public function updateAlbumPDFFormat(UpdateAlbumPDFFormatRequest $request, int $formatId)
    {
        $params = $request->only(['title', 'description', 'album_type_id', 'cover_page', 'content_page', 'last_page', 'number_images', 'content_page_id']);
        $result = $this->_albumPDFService->updateAlbumPDFFormat($params, $formatId);
        return Response::success([
            "album_pdf_format"  => new AlbumPDFFormatResource($result)
        ]);
    }

    public function deleteAlbumPDFFormat(int $formatId)
    {
        $this->_albumPDFService->deleteAlbumPDFFormat($formatId);
        return Response::success();
    }

    public function getConfigAlbumPDFFormat(Request $request)
    {
        $result = $this->_albumPDFService->getConfigAlbumPDFFormat();
        return Response::success($result);
    }
}
