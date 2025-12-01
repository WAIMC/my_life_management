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

class CredentialService
{
    public function __construct()
    {
    }

    /**
     * Handle login admin
     *
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function login(Request $request): array
    {
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
        $this->storeAccessTokenAndSetPermission($admin->id, $token['access_token']);
        $setRefreshCookie = $this->generateRefreshTokenForCookie($token['refresh_token']);
        $this->storeRefreshToken([
            $token['refresh_token'],
            $admin->id,
            $request,
        ]);

        return [
            'auth_type' => 'bearer',
            'ttl' => CommonVal::MAX_ACCESS_TTL,
            'access_token' => $token['access_token'],
            '_cookie' => $setRefreshCookie,
        ];
    }

    /**
     * Get and set permission for this user
     *
     * @param int $adminMstId
     * @param string $accessToken
     * @return void
     */
    private function storeAccessTokenAndSetPermission(int $adminMstId, string $accessToken): void
    {
        $parentKey = CommonVal::ADMIN_TYPE . ":{$adminMstId}";
        $tokenKey = $parentKey . ":{$accessToken}";
        Redis::hset($tokenKey, 'last_access_at', now());
        Redis::expire($tokenKey, CommonVal::MAX_ACCESS_TTL);

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
        Redis::expire($permissionTableKey, Redis::ttl($tokenKey));
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
    private function generateRefreshTokenForCookie(string|null $refreshToken): string
    {
        $ttl = $refreshToken ? (CommonVal::MAX_REFRESH_TTL / 60) : -1;
        $tokenValue = $refreshToken ?? null;

        return cookie(
            'refresh_token',
            $tokenValue,
            $ttl,
            '/',
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
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function refreshToken(Request $request): array
    {
        $refreshToken = $request->cookie('refresh_token');

        $adminMstId = $this->revokeToken($refreshToken, true);
        $token = $this->generateToken($adminMstId);
        $this->storeAccessTokenAndSetPermission($adminMstId, $token['access_token']);
        $this->storeRefreshToken([
            $token['refresh_token'],
            $adminMstId,
            $request,
        ]);
        $refreshCookie = $this->generateRefreshTokenForCookie($token['refresh_token']);

        return [
            'auth_type' => 'bearer',
            'ttl' => CommonVal::MAX_ACCESS_TTL,
            'access_token' => $token['access_token'],
            '_cookie' => $refreshCookie,
        ];
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

        // Revoke token
        if ($isRefresh) {
            // Delete refresh token in repo using deterministic hash
            $tokenId = md5($token);
            $refreshTokenData = TokenMst::where('token_hash', $tokenId)
                ->where('account_id', $credentials['id'])
                ->first();

            if (!$refreshTokenData) {
                throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
            }

            $refreshTokenData->delete();
        } else {
            // Delete access token in redis
            $parentKey = CommonVal::ADMIN_TYPE . ":{$credentials['id']}";
            $tokenKey = $parentKey . ":{$token}";
            Redis::del($tokenKey);
        }

        return $credentials['id'];
    }

    /**
     * Logout admin account
     *
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function logout(Request $request): array
    {
        $accessToken = $request->bearerToken();
        $refreshToken = $request->cookie('refresh_token');

        $this->revokeToken($accessToken, false);
        $this->revokeToken($refreshToken, true);
        $setRefreshCookie = $this->generateRefreshTokenForCookie(null);

        return [
            'auth_type' => 'bearer',
            'ttl' => CommonVal::MAX_ACCESS_TTL,
            'access_token' => null,
            '_cookie' => $setRefreshCookie,
        ];
    }

    /**
     * Summary of update refresh token in repo
     * 
     * @param mixed $payload
     * @return void
     */
    private function storeRefreshToken($payload):void
    {
        [$refreshToken, $accountId, $request] = $payload;

        if ($refreshToken) {
            // Generate a unique identifier for the token
            $tokenId = md5($refreshToken);
            
            TokenMst::create([
                'token_hash' => $tokenId, // Store deterministic hash instead of random hash
                'account_id'=> $accountId,
                'device_name' => $request->header('User-Agent'),
                'ip_address' => $request->ip(),
                'expired_at' => now()->addSeconds(value: CommonVal::MAX_REFRESH_TTL),
            ]);
        }
    }

    /**
     * Get current authenticated admin user
     *
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function me(Request $request): array
    {
        $accessToken = $request->bearerToken();
        
        if (!$accessToken) {
            throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
        }

        // Decode token to get user ID
        $payload = JsonWebToken::decode(
            $accessToken,
            env('ACCESS_TOKEN_SECRET'),
            false
        );
        $credentials = $payload['body'];

        // Get admin user information
        $admin = AdminMst::find($credentials['id']);
        
        if (!$admin) {
            throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
        }

        return [
            'id' => $admin->id,
            'user_name' => $admin->user_name,
            'full_name' => $admin->full_name,
            'email' => $admin->email,
            'phone' => $admin->phone,
            'status' => $admin->status,
            'is_active' => $admin->is_active,
        ];
    }
}
