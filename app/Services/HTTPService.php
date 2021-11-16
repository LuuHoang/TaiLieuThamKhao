<?php

namespace App\Services;

use App\Exceptions\SystemException;
use Illuminate\Support\Facades\Http;

class HTTPService extends AbstractService
{
    public function sendRequest(String $method, String $url, array $param = null) {
        try {
            $response = Http::withOptions(['verify' => false])->{$method}($url, $param);
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
        return $response;
    }
}
