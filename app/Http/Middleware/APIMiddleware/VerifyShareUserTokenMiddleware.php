<?php

namespace App\Http\Middleware\APIMiddleware;

use App\Services\WebService\AlbumService;
use Closure;
use Illuminate\Http\Request;

class VerifyShareUserTokenMiddleware
{
    private $_albumService;

    public function __construct(AlbumService $albumService)
    {
        $this->_albumService = $albumService;
    }

    public function handle($request, Closure $next)
    {
        $dataShareUser = $request->all(['token', 'password']);

        $shareUserEmail = $this->_albumService->getEmailOfShareUser($dataShareUser);

        app()->instance('shareUserEmail', $shareUserEmail);

        return $next($request);
    }
}
