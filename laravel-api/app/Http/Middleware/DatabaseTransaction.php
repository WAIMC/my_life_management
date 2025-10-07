<?php

namespace App\Http\Middleware;

use App\Constants\CommonVal;
use App\Enums\TypeOfMethod;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class DatabaseTransaction
{
    use ApiResponse;

    /**
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        $methodWrite = [
            TypeOfMethod::POST->label(),
            TypeOfMethod::PUT->label(),
            TypeOfMethod::PATCH->label(),
            TypeOfMethod::DELETE->label(),
        ];
        $isWriteOperation = in_array($request->method(), $methodWrite);

        if ($isWriteOperation) {
            DB::beginTransaction();
        }

        try {
            $response = $next($request);

            if (method_exists($response, 'getStatusCode')
                && $response->getStatusCode() >= CommonVal::HTTP_BAD_REQUEST) {
                if ($isWriteOperation) {
                    DB::rollBack();
                }
                return $response;
            }

            if ($isWriteOperation) {
                DB::commit();
            }

            $status = $response->getStatusCode();

            if ($response instanceof JsonResource) {
                $jsonResponse = $response->response($request);
                $data = $response->resolve($request);
                return self::successResponse($data, $jsonResponse->getStatusCode());
            }

            if ($response instanceof JsonResponse) {
                $data = $response->getData(true);
                return self::successResponse($data, $status);
            }

            if (method_exists($response, 'getContent')) {
                $content = $response->getContent();
                $decoded = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data = $decoded;
                } else {
                    $data = $content;
                }
                return self::successResponse($data, $status);
            }

            return self::successResponse($response, $status);

        } catch (Throwable $e) {
            if ($isWriteOperation) {
                DB::rollBack();
            }
            throw $e;
        }
    }
}
