<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Render response api
     *
     * @param mixed $data
     * @param array $error
     * @return JsonResponse
     */
    public static function renderResponse(mixed $data, array $error): JsonResponse
    {
        list($status, $code, $messages) = $error;

        return response()->json([
            'data' => $data,
            'error' => [
                'status' => $status,
                'code' => $code,
                'messages' => $messages
            ]
        ], $code);
    }

    /**
     * Success response
     *
     * @param mixed $data
     * @param int $code
     * @return JsonResponse
     */
    public static function successResponse(mixed $data, int $code = 200): JsonResponse
    {
        return self::renderResponse($data, [false, $code, null]);
    }

    /**
     * Error response
     *
     * @param mixed $message
     * @param int $code
     * @return JsonResponse
     */
    public static function errorResponse(mixed $message, int $code): JsonResponse
    {
        return self::renderResponse(null, [true, $code, $message]);
    }
}
