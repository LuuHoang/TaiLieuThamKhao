<?php

namespace App\Http\Middleware\APIMiddleware;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Services\MiddlewareService;
use Closure;
use Illuminate\Http\Request;

class CheckUserBelongToCompanyMiddleware
{
    private $_middlewareService;

    public function __construct(MiddlewareService $middlewareService)
    {
        $this->_middlewareService = $middlewareService;
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
        $userId = $request->userId;
        $this->_middlewareService->checkUserBelongToCompany($userId);
        return $next($request);
    }
}
