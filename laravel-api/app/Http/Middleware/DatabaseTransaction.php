<?php

namespace App\Http\Middleware;

use App\Constants\CommonVal;
use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DatabaseTransaction
{
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
        DB::beginTransaction();
        try {
            $response = $next($request);
            if (
                method_exists($response, 'getStatusCode')
                && $response->getStatusCode() >= CommonVal::HTTP_BAD_REQUEST
            ) {
                DB::rollBack();
            } else {
                DB::commit();
            }
            return $response;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
