<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Container\BindingResolutionException;
use App\Traits\ApiResponse;
use App\Constants\CommonVal;
use Throwable;
use LogicException;
use InvalidArgumentException;
use RuntimeException;
use DomainException;
use TypeError;
use ErrorException;
use ParseError;
use ReflectionException;
use App\Constants\Messages;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                $getValidCode = function($code, $default) {
                    return (is_numeric($code) && (int)$code > 0) ? (int)$code : $default;
                };
                switch (true) {
                    case $e instanceof AuthenticationException:
                    case $e instanceof TokenMismatchException:
                    case $e instanceof AuthorizationException:
                    case $e instanceof ThrottleRequestsException:
                    case $e instanceof MethodNotAllowedHttpException:
                    case $e instanceof NotFoundHttpException:
                    case $e instanceof HttpException:
                    case $e instanceof BadRequestHttpException:
                    case $e instanceof AccessDeniedHttpException:
                    case $e instanceof ModelNotFoundException:
                    case $e instanceof QueryException:
                    case $e instanceof BindingResolutionException:
                    case $e instanceof LogicException:
                    case $e instanceof RuntimeException:
                    case $e instanceof DomainException:
                    case $e instanceof TypeError:
                    case $e instanceof ErrorException:
                    case $e instanceof ParseError:
                    case $e instanceof ReflectionException:
                        return self::errorResponse(
                            $e->getMessage(),
                            $getValidCode($e->getCode() CommonVal::HTTP_INTERNAL_SERVER_ERROR),
                        );
                    case $e instanceof InvalidArgumentException:
                        $code = $getValidCode($e->getCode(), CommonVal::HTTP_UNPROCESSABLE_CONTENT);
                        return self::errorResponse(
                            $e->getMessage(),
                            $code
                        );
                    case $e instanceof ValidationException:
                        $code = $getValidCode($e->getCode(), CommonVal::HTTP_UNPROCESSABLE_CONTENT);
                        return self::errorResponse(
                            $e->errors(),
                            $code
                        );
                    default:
                        Log::error($e);
                        return self::errorResponse(
                            Messages::E0500,
                            CommonVal::HTTP_INTERNAL_SERVER_ERROR
                        );
                }
            }
        });
    }
}
