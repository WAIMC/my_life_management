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

        /**
         * Check access token had exited
         */
        $parentKey = CommonVal::ADMIN_TYPE . ":{$credentials['id']}";
        $tokenKey = $parentKey . ":{$token}:";
        if (!Redis::exists($tokenKey, 'id')) {
            throw new AuthorizationException(Messages::E0609, CommonVal::HTTP_UNAUTHORIZED);
        }

        $permissionTableKey = $parentKey . ":" . CommonVal::ADMIN_PERMISSION_TABLE;
        $method = strtoupper($request->method());
        $uri = $request->route()->uri();

        // Check permission access
        $pathsJson = Redis::hget($permissionTableKey, $method);
        if (!$pathsJson) {
            throw new NotFoundHttpException(Messages::E0404, null, CommonVal::HTTP_UNAUTHORIZED);
        }

        $allowedPaths = json_decode($pathsJson, true);
        $hasPermission = false;
        foreach ($allowedPaths as $pattern) {
            if ($this->matchUriPattern($uri, $pattern)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }

    /**
     * Match the request URI with the pattern route in DB/Redis.
     *
     * Supports:
     * - {id}, {slug} → match any 1 segment
     * - {id?}, {slug?} → optional segment
     * - * → wildcard (match all remaining segments)
     *
     * @param string $uri actual URI from request, e.g. "api/users/123"
     * @param string $pattern Pattern from DB, e.g. "api/users/{id}"
     * @return bool
     */
    private function matchUriPattern(string $uri, string $pattern): bool
    {
        // Standardize the removal of the terminal /
        $uri = trim($uri, '/');
        $pattern = trim($pattern, '/');

        if ($pattern === '*') {
            return true;
        }

        $uriParts = $uri === '' ? [] : explode('/', $uri);
        $patternParts = $pattern === '' ? [] : explode('/', $pattern);

        $uCount = count($uriParts);
        $pCount = count($patternParts);

        $i = 0;
        $j = 0;

        while ($i < $uCount && $j < $pCount) {
            $part = $patternParts[$j];
            $uriPart = $uriParts[$i];

            // Wildcard match
            if ($part === '*') {
                return true;
            }

            // {id} or {slug} → accept any segment
            if (preg_match('/^\{[^\/]+\}$/', $part)) {
                $i++; $j++;
                continue;
            }

            // {id?} or {slug?} → segment option
            if (preg_match('/^\{[^\/]+\?\}$/', $part)) {
                $i++;
                $j++;
                continue;
            }

            // Compare absolute
            if ($uriPart !== $part) {
                return false;
            }

            $i++; $j++;
        }

        // If still wildcard in pattern → OK
        if ($j < $pCount && $patternParts[$j] === '*') {
            return true;
        }

        // Allow pattern has param optional in last
        while ($j < $pCount && preg_match('/^\{[^\/]+\?\}$/', $patternParts[$j])) {
            $j++;
        }

        // Match only math 2
        return $i === $uCount && $j === $pCount;
    }
}
