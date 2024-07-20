<?php

namespace App\Http\Controllers\master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\master\AdminService;
use Symfony\Component\HttpFoundation\Response;

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
    $payload = $request->all();

    return $this->handleRequest(function () use ($payload) {
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
    $credentials = $request->only('user_name', 'password');
    return $this->handleRequest(function () use ($credentials) {
      $token = AdminService::getInstance()->login($credentials);

      return $this->respondWithToken($token);
    });
  }

  /**
   * Current auth
   * 
   * @return Response
   */
  public function me(): Response
  {
    return $this->handleRequest(function () {
      return $this->respondWithToken(true);
    });
  }

  // public function logout()
  // {
  //   Auth::logout();
  //   return response()->json(['message' => 'Successfully logged out']);
  // }

  // public function refresh()
  // {
  //   return $this->respondWithToken(Auth::refresh());
  // }
}
