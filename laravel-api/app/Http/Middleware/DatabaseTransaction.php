<?php

namespace App\Http\Middleware;

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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        DB::beginTransaction();
        try {
            $response = $next($request);
            if (
                method_exists($response, 'getStatusCode')
                && $response->getStatusCode() >= 400
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
