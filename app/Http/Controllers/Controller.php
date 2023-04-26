<?php

namespace App\Http\Controllers;

use Exception;
use FeedIo\Reader\ReadErrorException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @fun json
     * @param $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     * @date 2023/4/9
     * @author 刘铭熙
     */
    public function json($data, string $message = 'The request succeeded', int $code = 200): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'data' => $data,
            'message' => $message
        ], $code);
    }

    /**
     *
     * @fun error
     * @param Exception|ValidationException $exception
     * @return JsonResponse
     * @date 2023/4/9
     * @author 刘铭熙
     */
    public function error(Exception|ValidationException $exception): JsonResponse
    {
        $statusCode = $exception->status ??( $exception->getCode()>0 ? $exception->getCode() : 500); // 设置默认状态码为 500
        return response()->json([
            'code' => $statusCode,
            'message' => $exception->getMessage()
        ], $statusCode);
    }
}
