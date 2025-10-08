<?php

namespace App\Http\Middleware;

use App\Constants\CommonVal;
use App\Enums\TypeOfMethod;
use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Http\Request;

class TransactionMiddleware
{
    /**
     * Handle an incoming request with database transaction.
     * Commits on successful response, rolls back on exceptions.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): mixed
    {
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

            return $response;
        } catch (Throwable $e) {
            if ($isWriteOperation) {
                DB::rollBack();
            }
            throw $e;
        }
    }
}
