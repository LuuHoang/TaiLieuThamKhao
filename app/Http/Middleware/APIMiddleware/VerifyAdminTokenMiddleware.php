<?php

namespace App\Http\Middleware\APIMiddleware;

use App\Exceptions\UnauthorizedException;
use App\Services\AdminService;
use Closure;
use Illuminate\Http\Request;

class VerifyAdminTokenMiddleware
{
    private $_adminService;

    public function __construct(AdminService $adminService)
    {
        $this->_adminService = $adminService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws UnauthorizedException
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            throw new UnauthorizedException('messages.login_to_perform_function');
        }

        $admin = $this->_adminService->getAdminByToken($token);

        app()->instance('currentAdmin', $admin);

        return $next($request);
    }
}
