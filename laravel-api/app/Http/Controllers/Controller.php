<?php

namespace App\Http\Controllers;

use Exception;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Utilities\JsonWebToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Controller
{
  /**
   * Handle request common
   * 
   * @param mixed @callback
   * @return Response
   */
  public static function handleRequest(mixed $callback): Response
  {
    DB::beginTransaction();
    try {
      $data = $callback();
      DB::commit();

      return self::renderResponse(
        $data,
        [false, CommonVal::HTTP_OK, null]
      );
    } catch (
      AuthenticationException
      | TokenMismatchException $e
    ) {
      var_dump('Error 1 :', $e->getMessage());
      DB::rollBack();

      return self::renderResponse(
        false,
        [true, $e->getCode(), $e->getMessage()]
      );
    } catch (
      AuthorizationException
      | ThrottleRequestsException
      | MethodNotAllowedHttpException
      | NotFoundHttpException
      | HttpException $e
    ) {
      var_dump('Error 2 :', $e->getMessage());
      return self::renderResponse(
        false,
        [true, $e->getCode(), $e->getMessage()]
      );
    } catch (ValidationException $e) {
      var_dump('Error 3 :', $e->getMessage());
      return self::renderResponse(
        false,
        [
          true,
          $e->getCode() ?: CommonVal::HTTP_UNPROCESSABLE_CONTENT,
          $e->validator->errors()->messages()
        ]
      );
    } catch (Exception $e) {
      DB::rollBack();
      var_dump('Error 4 :', $e->getMessage());

      return self::renderResponse(
        false,
        [true, CommonVal::HTTP_INTERNAL_SERVER_ERROR, Messages::E0500]
      );
    }
  }

  /**
   * Render response api
   * 
   * @param mixed $data
   * @param array $error
   * @return Response
   */
  private static function renderResponse(mixed $data, array $error): Response
  {
    list($status, $code, $messages) = $error;

    return response()->json([
      'data' => $data,
      'error' => [
        'status' => $status,
        'code' => $code,
        'messages' => $messages
      ]
    ]);
  }

  /**
   * Render data token
   * 
   * @param string $token
   * @return array
   */
  protected function respondWithToken($token): array
  {
    return [
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => JsonWebToken::TTL
    ];
  }
}
