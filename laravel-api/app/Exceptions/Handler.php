<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpFoundation\Response;
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
use App\Constants\Messages;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse|Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): JsonResponse|Response
    {
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions and format response
     *
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse
     */
    private function handleApiException(Request $request, Throwable $e): JsonResponse
    {
        $getValidCode = function ($code, $default) {
            return (is_numeric($code) && (int)$code > 0) ? (int)$code : $default;
        };

        $code = $getValidCode($e->getCode(), CommonVal::HTTP_INTERNAL_SERVER_ERROR);
        $message = $e->getMessage() ?: Messages::E0500;

        switch (true) {
            case $e instanceof AuthenticationException:
            case $e instanceof TokenMismatchException:
            case $e instanceof AuthorizationException:
            case $e instanceof AccessDeniedHttpException:
                $code = $getValidCode($e->getCode(), CommonVal::HTTP_UNAUTHORIZED);
                return self::errorResponse($message, $code);

            case $e instanceof ThrottleRequestsException:
                $code = $getValidCode($e->getCode(), CommonVal::HTTP_TOO_MANY_REQUESTS);
                return self::errorResponse($message, $code);

            case $e instanceof MethodNotAllowedHttpException:
                $code = $getValidCode($e->getCode(), CommonVal::HTTP_METHOD_NOT_ALLOWED);
                return self::errorResponse($message, $code);

            case $e instanceof NotFoundHttpException:
            case $e instanceof ModelNotFoundException:
                $code = $getValidCode($e->getCode(), CommonVal::HTTP_NOT_FOUND);
                return self::errorResponse($message, $code);

            case $e instanceof HttpException:
            case $e instanceof BadRequestHttpException:
                $code = $getValidCode($e->getCode(), CommonVal::HTTP_BAD_REQUEST);
                return self::errorResponse($message, $code);

            case $e instanceof QueryException:
            case $e instanceof BindingResolutionException:
            case $e instanceof LogicException:
            case $e instanceof RuntimeException:
            case $e instanceof DomainException:
            case $e instanceof TypeError:
            case $e instanceof ErrorException:
            case $e instanceof ParseError:
                return self::errorResponse($message, $code);

            case $e instanceof InvalidArgumentException:
                $code = $getValidCode($e->getCode(), CommonVal::HTTP_UNPROCESSABLE_CONTENT);
                return self::errorResponse($message, $code);

            default:
                Log::error($e);
                return self::errorResponse(
                    Messages::E0500,
                    CommonVal::HTTP_INTERNAL_SERVER_ERROR
                );
        }
    }
}
