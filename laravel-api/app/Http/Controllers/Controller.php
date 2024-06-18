<?php

namespace App\Http\Controllers;

use Exception;
use App\Common;
use App\Messages;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

abstract class Controller
{
  /**
   * Handle request common
   * @param mixed @callback
   * @return array
   */
  public function handleRequest(mixed $callback): array
  {
    DB::beginTransaction();
    try {
      $data = $callback();
      DB::commit();

      return $this->renderResponse(
        $data,
        [false, Common::HTTP_OK, null]
      );
    } catch (
      AuthenticationException
      | TokenMismatchException $e
    ) {
      DB::rollBack();

      return $this->renderResponse(
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
      return $this->renderResponse(
        false,
        [true, $e->getCode(), $e->getMessage()]
      );
    } catch (ValidationException $e) {
      return $this->renderResponse(
        false,
        [
          true,
          $e->getCode() ?: Common::HTTP_UNPROCESSABLE_CONTENT,
          $e->validator->errors()->messages()
        ]
      );
    } catch (Exception $e) {
      DB::rollBack();

      return $this->renderResponse(
        false,
        [true, Common::HTTP_INTERNAL_SERVER_ERROR, Messages::E0500]
      );
    }
  }

  /**
   * Render response api
   * 
   * @param mixed $data
   * @param array $error
   * @return array
   */
  public function renderResponse(mixed $data, array $error): array
  {
    list($status, $code, $messages) = $error;

    return [
      'data' => $data,
      'error' => [
        'status' => $status,
        'code' => $code,
        'messages' => $messages
      ]
    ];
  }
}
