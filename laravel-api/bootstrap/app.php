<?php

use App\Constants\CommonVal;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Constants\Messages;
use App\Traits\ApiResponse;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    channels: __DIR__ . '/../routes/channels.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {})
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (Throwable $e, Request $request): JsonResponse|null {
      if (!$request->expectsJson()) {
        return null; // Fall back to default handling for non-API requests
      }

      $handler = new class {
        use ApiResponse;
      };

      $getValidCode = function ($code, $default) {
        return (is_numeric($code) && (int)$code > 0) ? (int)$code : $default;
      };

      $code = $getValidCode($e->getCode(), CommonVal::HTTP_INTERNAL_SERVER_ERROR);
      $message = $e->getMessage() ?: Messages::E0500;

      switch (true) {
        case $e instanceof ValidationException:
          $code = $getValidCode($e->getCode(), CommonVal::HTTP_UNPROCESSABLE_CONTENT);
          $message = $e->errors();

          return $handler->errorResponse($message, $code);

        case $e instanceof AuthenticationException:
        case $e instanceof TokenMismatchException:
        case $e instanceof AuthorizationException:
        case $e instanceof AccessDeniedHttpException:
          $code = $getValidCode($e->getCode(), CommonVal::HTTP_UNAUTHORIZED);
          return $handler->errorResponse($message, $code);

        case $e instanceof ThrottleRequestsException:
          $code = $getValidCode($e->getCode(), CommonVal::HTTP_TOO_MANY_REQUESTS);
          return $handler->errorResponse($message, $code);

        case $e instanceof MethodNotAllowedHttpException:
          $code = $getValidCode($e->getCode(), CommonVal::HTTP_METHOD_NOT_ALLOWED);
          return $handler->errorResponse($message, $code);

        case $e instanceof NotFoundHttpException:
        case $e instanceof ModelNotFoundException:
          $code = $getValidCode($e->getCode(), CommonVal::HTTP_NOT_FOUND);
          return $handler->errorResponse($message, $code);

        case $e instanceof HttpException:
        case $e instanceof BadRequestHttpException:
          $code = $getValidCode($e->getCode(), CommonVal::HTTP_BAD_REQUEST);
          return $handler->errorResponse($message, $code);

        case $e instanceof QueryException:
        case $e instanceof BindingResolutionException:
        case $e instanceof LogicException:
        case $e instanceof RuntimeException:
        case $e instanceof TypeError:
        case $e instanceof ErrorException:
        case $e instanceof ParseError:
          return $handler->errorResponse($message, CommonVal::HTTP_INTERNAL_SERVER_ERROR);

        default:
          Log::error($e);
          return $handler->errorResponse(
            Messages::E0500,
            CommonVal::HTTP_INTERNAL_SERVER_ERROR
          );
      }
    });
  })->create();
