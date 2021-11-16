<?php

namespace App\Supports\Components\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use ArrayObject;

/**
 * Class ResponseFormat
 * @package App\Supports\Components\Response
 */
class ResponseFormat
{
    /**
     * @param array $data
     *
     * @return JsonResponse
     */
    public function success(array $data = []): JsonResponse
    {
        return response()->json([
            'code' => JsonResponse::HTTP_OK,
            'data' => empty($data) ? new ArrayObject : $data,
        ]);
    }

    /**
     * @param string $keyMessage
     * @param int   $status
     *
     * @return JsonResponse
     */
    public function failure(string $keyMessage, int $status = JsonResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        $currentUser = app()->bound('currentUser') ? app('currentUser') : null;
        $lang = $currentUser->userSetting->language ?? config('app.locale');
        $lang = strtolower($lang);

        return response()->json([
            'code' => $status,
            'message' => trans($keyMessage, [], $lang)
        ], $status);
    }

    /**
     * @param AnonymousResourceCollection $data
     * @param int   $total
     * @param int   $current
     * @param int   $limit
     *
     * @return JsonResponse
     */
    public function pagination(AnonymousResourceCollection $data, int $total, int $current, int $limit): JsonResponse
    {
        return response()->json([
            'code' => JsonResponse::HTTP_OK,
            'data' => [
                'data_list' => $data,
                'total' => $total,
                'current_page' => $current,
                'limit' => $limit,
            ],
        ]);
    }

}
