<?php

namespace App\Http\Middleware\APIMiddleware;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Services\MiddlewareService;
use Closure;
use Illuminate\Http\Request;

class CheckAlbumBelongToUserMiddleware
{
    private $_middlewareService;

    public function __construct(MiddlewareService $_middlewareService)
    {
        $this->_middlewareService = $_middlewareService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function handle($request, Closure $next)
    {
        $albumId = $request->albumId;
        $this->_middlewareService->checkAlbumBelongToUser($albumId);
        return $next($request);
    }
}
