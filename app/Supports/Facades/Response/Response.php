<?php

namespace App\Supports\Facades\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Facade;

/**
 * Class Response
 * @package App
 *
 * @method static JsonResponse success(array $data = [])
 * @method static JsonResponse failure(array $data, int $status = 500)
 * @method static JsonResponse pagination(AnonymousResourceCollection $data, int $total, int $current, int $limit)
 * @method static JsonResponse notFound()
 */
class Response extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'response';
    }
}
