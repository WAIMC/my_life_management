<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Constants\Messages;
use App\constants\CommonVal;
use App\Models\master\Admin;
use Illuminate\Http\Request;
use App\Utilities\JsonWebToken;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $token = $request->bearerToken();

    // Check existing access token
    if (!$token) {
      return Controller::handleRequest(function () {
        throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
      });
    }

    try {
      $payload = JsonWebToken::decode($token, env('ACCESS_TOKEN_SECRET'));
      $credentials = $payload['body'];
      // Check request from member type admin
      if ($credentials['type'] !== Admin::TYPE) {
        throw new \UnexpectedValueException(Messages::E0608);
      }

      // Check permission access
      $adminPermission = DB::table('admin_permission_view')
        ->where('admin_id', $credentials['id'])
        ->where('path', $request->route()->uri())
        ->where('type', $request->method())
        ->first();

      if (!$adminPermission) {
        throw new NotFoundHttpException(Messages::E0404);
      }
    } catch (NotFoundHttpException $e) {
      return Controller::handleRequest(function () use ($e) {
        throw new NotFoundHttpException($e->getMessage(), null, CommonVal::HTTP_UNAUTHORIZED);
      });
    } catch (Exception $e) {
      return Controller::handleRequest(function () {
        throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
      });
    }

    // Set info for request
    $request->attributes->set('admin_id',  $adminPermission->admin_id);
    $response = $next($request);

    return $response;
  }
}
