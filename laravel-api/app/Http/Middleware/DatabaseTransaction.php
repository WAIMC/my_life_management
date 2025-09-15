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

class DatabaseTransaction
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Optionally:: only active for methods write
        $methodWrite = [
            TypeOfMethod::POST->label(),
            TypeOfMethod::PUT->label(),
            TypeOfMethod::PATCH->label(),
            TypeOfMethod::DELETE->label(),
        ];
        if (!in_array($request->method(), $methodWrite)) {
            return self::successResponse($response);
        }

        DB::beginTransaction();
        try {
            if (
                method_exists($response, 'getStatusCode')
                && $response->getStatusCode() >= CommonVal::HTTP_BAD_REQUEST
            ) {
                DB::rollBack();
                return $response;
            } else {
                DB::commit();
                return self::successResponse($response);
            }
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
