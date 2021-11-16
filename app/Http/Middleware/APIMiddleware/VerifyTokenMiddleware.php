<?php

namespace App\Http\Middleware\APIMiddleware;

use App\Exceptions\UnauthorizedException;
use App\Services\APIServices\UserService;
use Closure;
use Illuminate\Http\Request;

class VerifyTokenMiddleware
{
    private $_userService;

    public function __construct(UserService $userService)
    {
        $this->_userService = $userService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws UnauthorizedException
     * @throws \App\Exceptions\AuthenticationDenied
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            throw new UnauthorizedException('messages.login_to_perform_function');
        }

        $user = $this->_userService->getUserByToken($token);

        app()->instance('currentUser', $user);

        return $next($request);
    }
}
