<?php

namespace App\Http\Controllers\master;

use App\constants\CommonVal;
use App\Constants\Messages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\master\api;
use App\Services\master\AdminService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class AdminController extends Controller
{
  /**
   * Register admin account
   * 
   * @param Request $request
   * @return Response
   */
  public function register(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      // Check valid method
      if ($request->method() !== api::TYPE_OF_METHOD[1]) {
        throw new MethodNotAllowedException(
          [api::TYPE_OF_METHOD[1]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }

      $payload = $request->all();

      return AdminService::getInstance()->register($payload);
    });
  }

  /**
   * Login admin account
   * 
   * @param Request $request
   * @return Response
   */
  public function login(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      // Check valid method
      if ($request->method() !== api::TYPE_OF_METHOD[1]) {
        throw new MethodNotAllowedException(
          [api::TYPE_OF_METHOD[1]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }
      $credentials = $request->only('user_name', 'password');
      $token = AdminService::getInstance()->login($credentials);

      return $this->respondWithToken($token);
    });
  }

  /**
   * Current auth
   * 
   * @param Request $request
   * @return Response
   */
  public function me(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      // Check valid method
      if ($request->method() !== api::TYPE_OF_METHOD[0]) {
        throw new MethodNotAllowedException(
          [api::TYPE_OF_METHOD[0]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }

      return $this->respondWithToken(true);
    });
  }

  /**
   * Logout admin account
   * 
   * @param Request $request
   * @return Response
   */
  public function logout(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      // Check valid method
      if ($request->method() !== api::TYPE_OF_METHOD[1]) {
        throw new MethodNotAllowedException(
          [api::TYPE_OF_METHOD[1]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }

      return AdminService::getInstance()->logout($request);
    });
  }
}
