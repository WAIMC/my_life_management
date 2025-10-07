<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Constants\Messages;
use App\Constants\CommonVal;
use Illuminate\Http\Request;
use App\Utilities\JsonWebToken;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UnexpectedValueException;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        // Check existing access token
        if (!$token) {
            throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
        }

        $payload = JsonWebToken::decode($token, env('ACCESS_TOKEN_SECRET'));
        $credentials = $payload['body'];
        // Check request from member type admin
        if ($credentials['type'] !== CommonVal::ADMIN_TYPE) {
            throw new UnexpectedValueException(Messages::E0608);
        }

        // Check permission access
        $cached = Redis::get(CommonVal::ADMIN_PERMISSION_TABLE . ":{$credentials['id']}");
        if (!$cached) {
            throw new NotFoundHttpException(Messages::E0404, null, CommonVal::HTTP_UNAUTHORIZED);
        }

        $permissions = json_decode($cached, true);
        $method = $request->getMethod();
        $currentUri = $request->route()->uri();

        // Check if the admin has permission to access this route
        $hasPermission = false;
        foreach ($permissions as $permission) {
            // Compare method and uri pattern
            if ($permission['type'] === $method) {
                $permissionUri = ltrim($permission['path'], '/');

                // Convert route parameters format from both sides to ensure consistent comparison
                // e.g., "api/admin/user-mgmt/show/{id}" matches "api/admin/user-mgmt/show/{userId}"
                $pattern1 = preg_replace('/\{[^\/]+\}/', '{param}', $permissionUri);
                $pattern2 = preg_replace('/\{[^\/]+\}/', '{param}', $currentUri);

                if ($pattern1 === $pattern2) {
                    $hasPermission = true;
                    break;
                }
            }
        }

        if (!$hasPermission) {
            throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
