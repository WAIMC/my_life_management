<?php

namespace App\Services\Custom;

use App\Models\Master\AdminMst;
use App\Utilities\JsonWebToken;
use App\Constants\Messages;
use App\Constants\CommonVal;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Redis;
use UnexpectedValueException;
use App\Models\Master\TokenMst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CredentialService
{
    public function __construct()
    {
    }

    /**
     * Handle login admin
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function login(Request $request): JsonResponse
    {
        dd('aaa');
        $credentials = $request->only('user_name', 'password');
        $admin = AdminMst::where('user_name', $credentials['user_name'])->first();

        // Check is valid credential
        if (!$admin) {
            throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
        } else if ($admin->limit_access >= CommonVal::LIMIT_ACCESS_FAIL) {
            throw new AuthorizationException(Messages::E0610, CommonVal::HTTP_UNAUTHORIZED);
        } else if (!Hash::check($credentials['password'], $admin->password)) {
            $admin->limit_access += 1;
            $admin->save();
            throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
        } else {
            $admin->limit_access = 0;
            $admin->save();
        }

        $token = $this->generateToken($admin->id);
        $this->getAndSetPermission($admin->id, $token['access_token']);
        $refreshCookie = $this->updateRefreshTokenInCookie($token['refresh_token']);
        $this->updateRefreshTokenInRepo([
            null,
            $refreshCookie,
            $admin->id,
            $request,
        ]);

        return response()->json([
                'auth_type' => 'bearer',
                'ttl' => CommonVal::MAX_ACCESS_TTL,
                'access_token' => $token['access_token'],
            ])
            ->withCookie($refreshCookie);
    }

    /**
     * Get and set permission for this user
     *
     * @param int $adminMstId
     * @param string $accessToken
     * @return void
     */
    private function getAndSetPermission(int $adminMstId, string $accessToken): void
    {
        $parentKey = CommonVal::ADMIN_TYPE . ":{$adminMstId}";
        $tokenKey = $parentKey . ":{$accessToken}:";
        Redis::hset($tokenKey, 'last_access_at', now());
        Redis::expire($tokenKey, CommonVal::MAX_ACCESS_TTL);

        // Update parent key's expire time to match the tokenKey
        Redis::expire($parentKey, Redis::ttl($tokenKey));

        // Check if parent Key exists in Redis hash
        $permissionTableKey = $parentKey . ":" . CommonVal::ADMIN_PERMISSION_TABLE;
        if (!Redis::exists($permissionTableKey)) {
            /**
             * Get and set permission for this user
             */
            $permissions = DB::table('admin_permission_view')
                ->where('admin_mst_id', $adminMstId)
                ->select('type', 'path')
                ->distinct()
                ->get()
                ->toArray();

            // Group path by method
            $groupedPermissions = collect($permissions)
                ->groupBy(fn($item) => strtoupper($item->type))
                ->map(fn($items) => $items->pluck('path')->unique()->values()->toArray())
                ->toArray();

            foreach ($groupedPermissions as $method => $paths) {
                Redis::hset($permissionTableKey, $method, json_encode($paths));
            }
        }
    }

    /**
     * Generate token
     *
     * @param int $adminMstId
     * @return array
     */
    private function generateToken(int $adminMstId): array
    {
        /**
         * Get, set access and refresh token for this user
         */
        $payload = [
            'id' => (string)$adminMstId,
            'type' => CommonVal::ADMIN_TYPE,
        ];

        // Generate new access token
        $accessToken = JsonWebToken::encode(
            JsonWebToken::JWTPayload($payload, false),
            env('ACCESS_TOKEN_SECRET')
        );

        // Generate new refresh token
        $refreshToken = JsonWebToken::encode(
            JsonWebToken::JWTPayload($payload, true),
            env('REFRESH_TOKEN_SECRET')
        );

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }

    /**
     * Generate cookie for token
     *
     * @param string|null $refreshToken
     * @return string
     */
    private function updateRefreshTokenInCookie(string|null $refreshToken): string
    {
        $ttl = $refreshToken ? CommonVal::MAX_REFRESH_TTL / 60 : -1;
        $tokenValue = $refreshToken ?? null;

        return cookie(
            'refresh_token',
            $tokenValue,
            $ttl,
            'api/admin/account',
            config('session.domain'),
            app()->environment('production'),
            true,
            false,
            'Strict'
        );
    }

    /**
     * Handle refresh token
     *
     * @param string|null $refreshToken
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function refreshToken(string|null $refreshToken): JsonResponse
    {
        $adminMstId = $this->revokeToken($refreshToken, true);
        $token = $this->generateToken($adminMstId);
        $this->getAndSetPermission($adminMstId, $token['access_token']);
        $data = $this->updateRefreshTokenInCookie($token['access_token'], $token['refresh_token']);

        return response()->json([])
            ->withCookie($data['set_access_token_cookie'])
            ->withCookie($data['set_refresh_token_cookie']);
    }

    /**
     * Revoke token (add to black list)
     *
     * @param string $token
     * @param bool $isRefresh
     * @throws AuthorizationException
     * @throws \UnexpectedValueException
     * @return int
     */
    private function revokeToken(string $token, bool $isRefresh): int
    {
        // Check existing access token
        if (!$token) {
            throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
        }

        $payload = JsonWebToken::decode(
            $token,
            $isRefresh ? env('REFRESH_TOKEN_SECRET') : env('ACCESS_TOKEN_SECRET'),
            $isRefresh
        );
        $credentials = $payload['body'];
        // Check request from member type admin
        if ($credentials['type'] !== CommonVal::ADMIN_TYPE) {
            throw new UnexpectedValueException(Messages::E0608);
        }

        /**
         * Check token had exited in black list
         */
        $tokenKeyType = ($isRefresh ? CommonVal::BLACKLIST_REFRESH_TOKEN : CommonVal::BLACKLIST_ACCESS_TOKEN);
        $key = $tokenKeyType . ':' . $token;
        if (Redis::hget($key, 'id')) {
            throw new AuthorizationException(Messages::E0609, CommonVal::HTTP_UNAUTHORIZED);
        }

        // Add token to black list
        Redis::hset($key, 'id', $credentials['id']);
        Redis::expireat($key, $credentials['exp']);

        return $credentials['id'];
    }

    /**
     * Logout admin account
     *
     * @param string|null $accessToken
     * @param string|null $refreshToken
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function logout(string|null $accessToken, string|null $refreshToken): JsonResponse
    {
        $this->revokeToken($accessToken, false);
        $this->revokeToken($refreshToken, true);

        // Delete permission cache if exist
        $key = CommonVal::ADMIN_PERMISSION_TABLE . ":{$accessToken}";
        Redis::del($key);

        $data = $this->updateRefreshTokenInCookie(null, null);

        return response()->json([])
            ->withCookie($data['set_access_token_cookie'])
            ->withCookie($data['set_refresh_token_cookie']);
    }

    /**
     * Summary of update refresh token in repo
     * 
     * @param mixed $payload
     * @return void
     */
    private function updateRefreshTokenInRepo($payload):void
    {
        [$refreshTokenOld, $refreshTokenNew, $accountId, $request] = $payload;

        if ($refreshTokenOld) {
            $hashedToken = Hash::make($refreshTokenOld);
            TokenMst::where('token_hash', $hashedToken)->delete();
        }

        if ($refreshTokenNew) {
            TokenMst::create([
                'token_hash' => Hash::make($refreshTokenNew),
                'account_id'=> $accountId,
                'device_name' => $request->header('User-Agent'),
                'ip_address' => $request->ip(),
                'expired_at' => now()->addSeconds(value: CommonVal::MAX_REFRESH_TTL),
            ]);
        }
    }
}
