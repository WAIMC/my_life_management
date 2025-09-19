<?php

namespace App\Services\Master;

use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Interfaces\Master\AdminMstInterface;
use App\Models\Master\AdminMst;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Utilities\JsonWebToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Http\Resources\Master\AdminResource;
use Illuminate\Auth\Access\AuthorizationException;

class AdminMstService
{
    public function __construct(
        private AdminMstInterface $admin
    )
    {
    }

    /**
     * Handle find admin list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->admin->list($payload);

        return AdminResource::collection($list);
    }

    /**
     * Handle store account admin
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->admin->executeStore($payload);
    }

    /**
     * Handle update account
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->admin->executeUpdate($payload);
    }

    /**
     * Delete account
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->admin->executeDelete($payload['ids']);
    }

    /**
     * Handle login account
     *
     * @param array $payload
     * @return array
     * @throws AuthorizationException
     */
    public function login(array $payload): array
    {
        $admin = AdminMst::where('user_name', $payload['user_name'])->first();

        if (!$admin || !Hash::check($payload['password'], $admin->password)) {
            throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
        }

        $payload = [
            'id' => (string)$admin->id,
            'type' => CommonVal::ADMIN_TYPE,
            'role' => 'admin'
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
            'expires_on' => time() + JsonWebToken::TTL_ACCESS,
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
