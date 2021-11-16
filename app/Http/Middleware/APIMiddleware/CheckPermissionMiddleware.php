<?php

namespace App\Http\Middleware\APIMiddleware;

use App\Exceptions\PermissionDeniedException;
use App\Services\CommonService;
use Closure;
use Illuminate\Http\Request;

class CheckPermissionMiddleware
{
    private $_commonService;

    public function __construct(CommonService $commonService)
    {
        $this->_commonService = $commonService;
    }

    public function handle(Request $request, Closure $next, $platform, ...$permissions)
    {
        $currentUser = app('currentUser');
        foreach ($permissions as $permission) {
            if (!$this->_commonService->checkPermission(json_decode($currentUser->userRole->permissions ?? '[]', true), $permission, $platform)) {
                throw new PermissionDeniedException('messages.not_have_permission');
            }
        }
        return $next($request);
    }
}
