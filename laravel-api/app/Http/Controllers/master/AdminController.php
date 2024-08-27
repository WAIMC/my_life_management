<?php

namespace App\Http\Controllers\master;

use App\constants\CommonVal;
use App\Constants\Messages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\master\Api;
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
      if ($request->method() !== Api::TYPE_OF_METHOD[1]) {
        throw new MethodNotAllowedException(
          [Api::TYPE_OF_METHOD[1]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }

      $payload = $request->all();
      $payload['admin_id'] = $request->attributes->get('admin_id');

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
      if ($request->method() !== Api::TYPE_OF_METHOD[1]) {
        throw new MethodNotAllowedException(
          [Api::TYPE_OF_METHOD[1]],
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
      if ($request->method() !== Api::TYPE_OF_METHOD[0]) {
        throw new MethodNotAllowedException(
          [Api::TYPE_OF_METHOD[0]],
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
      if ($request->method() !== Api::TYPE_OF_METHOD[1]) {
        throw new MethodNotAllowedException(
          [Api::TYPE_OF_METHOD[1]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }

      return AdminService::getInstance()->logout($request);
    });
  }

  /**
   * Update account
   * 
   * @param Request $request
   * @return Response
   */
  public function update(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      // Check valid method
      if ($request->method() !== Api::TYPE_OF_METHOD[2]) {
        throw new MethodNotAllowedException(
          [Api::TYPE_OF_METHOD[2]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }

      $payload = $request->all();
      $payload['admin_id'] = $request->attributes->get('admin_id');

      return AdminService::getInstance()->update($payload);
    });
  }
}
