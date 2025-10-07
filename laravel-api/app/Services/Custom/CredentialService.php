<?php

namespace App\Services\Custom;

use App\Models\Master\AdminMst;
use App\Utilities\JsonWebToken;
use App\Constants\Messages;
use App\Constants\CommonVal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Redis;

class CredentialService
{
    public function __construct()
    {
    }

    /**
     * Handle login admin
     *
     * @param array $payload
     * @return array
     * @throws AuthorizationException
     */
    public function login(array $payload): array
    {
        $admin = AdminMst::where('user_name', $payload['user_name'])->first();

        /**
         * Check and lock account if login fail >= 5 time
         */
        if (!$admin || $admin->limit_access >= 5) {
            throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
        }

        if (!Hash::check($payload['password'], $admin->password)) {
            $admin->limit_access = $payload['limit_access'] + 1;
            throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
        } else {
            $admin->limit_access = 0;
        }

        $admin->save();

        /**
         * Get and set permission for this user
         */
        $permissions = DB::table('admin_permission_view')
            ->where('admin_mst_id', $admin->id)
            ->select('type', 'path')
            ->distinct()
            ->get()
            ->toArray();

        Redis::set(CommonVal::ADMIN_PERMISSION_TABLE . ":{$admin->id}", json_encode($permissions));
        Redis::expire(CommonVal::ADMIN_PERMISSION_TABLE . ":{$admin->id}", CommonVal::MAX_TTL);

        /**
         * Get, set access and refresh token for this user
         */
        $payload = [
            'id' => (string)$admin->id,
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
            'token_type' => 'bearer',
            'expires_on' => time() + CommonVal::MAX_TTL,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }

    /**
     * Handle refresh token
     *
     * @param string|null $refreshToken
     * @return array
     * @throws AuthorizationException
     */
    public function refreshToken(string|null $refreshToken): array
    {
        $payload = JsonWebToken::decode($refreshToken, env('REFRESH_TOKEN_SECRET'), true);

        // Check refresh token had exited in black list
        $key = CommonVal::BLACKLIST . ':' . CommonVal::ADMIN_TYPE . ':' . $payload['signature'];
        if (Redis::hget($key, 'id')) {
            throw new AuthorizationException(Messages::E0609, CommonVal::HTTP_UNAUTHORIZED);
        }

        // Generate new access token
        $accessToken = JsonWebToken::encode(
            JsonWebToken::JWTPayload($payload['body'], false),
            env('ACCESS_TOKEN_SECRET')
        );

        // Generate new refresh token
        $refreshToken = JsonWebToken::encode(
            JsonWebToken::JWTPayload($payload['body'], true),
            env('REFRESH_TOKEN_SECRET')
        );

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }

    /**
     * Logout admin account
     *
     * @param string|null $refreshToken
     * @return array
     * @throws AuthorizationException
     */
    public function logout(string|null $refreshToken): array
    {
        $payload = JsonWebToken::decode($refreshToken, env('REFRESH_TOKEN_SECRET'), true);
        $body = $payload['body'];

        $key = CommonVal::BLACKLIST . ':' . CommonVal::ADMIN_TYPE . ':' . $payload['signature'];

        if (Redis::hget($key, 'id')) { // Check already exit in blacklist
            throw new AuthorizationException(Messages::E0609, CommonVal::HTTP_UNAUTHORIZED);
        } else { // Create token and set expired time in blacklist
            Redis::hmset($key, $body);
            Redis::expireat($key, $body['exp']);
        }

        return [];
    }
}
