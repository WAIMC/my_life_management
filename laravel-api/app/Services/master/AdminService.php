<?php

namespace App\Services\master;

use App\Constants\Messages;
use App\constants\CommonVal;
use App\Models\master\Admin;
use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Services\CommonService;
use App\Utilities\JsonWebToken;
use App\Services\SingletonService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Repositories\master\RoleRepository;

use App\Http\Resources\master\AdminResource;
use App\Repositories\master\AdminRepository;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\master\admin\AdminListRequest;
use App\Http\Requests\master\admin\AdminStoreRequest;
use App\Http\Requests\master\admin\AdminUpdateRequest;

class AdminService extends SingletonService
{
  /**
   * Handle find admin list
   * 
   * @param array @payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    // Validate
    $validator = (new CommonService())->validationManual(
      (new AdminListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    // Check is role admin root
    if (!RoleRepository::isRoot($payload["admin_id"])) {
      throw new AuthorizationException(Messages::E0403, CommonVal::HTTP_FORBIDDEN);
    }

    $list = AdminRepository::list($payload);

    return $list
      ? AdminResource::collection($list)
      : [];
  }

  /**
   * Handle store account admin
   * 
   * @param array @payload
   * @return bool
   */
  public function store(array $payload): bool
  {
    // Validate
    $validator = (new CommonService())->validationManual(
      (new AdminStoreRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    // Check is role admin root
    if (!RoleRepository::isRoot($payload["admin_id"])) {
      throw new AuthorizationException(Messages::E0403, CommonVal::HTTP_FORBIDDEN);
    }

    AdminRepository::store($payload);

    return true;
  }

  /**
   * Handle update account
   * 
   * @param array @payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new AdminUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    AdminRepository::update($payload);

    return true;
  }

  /**
   * Handle login account
   * 
   * @param array $payload
   * @return array
   */
  public function login($payload): array
  {
    $admin = Admin::where('user_name', $payload['user_name'])->first();

    if (!$admin || !Hash::check($payload['password'], $admin->password)) {
      throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
    }

    $payload = [
      'id' => (string)$admin->id,
      'type' => Admin::TYPE,
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
      'token_type'    => 'bearer',
      'expires_on'    => time() + JsonWebToken::TTL_ACCESS,
      'access_token'  => $accessToken,
      'refresh_token' => $refreshToken
    ];
  }

  /**
   * Handle refresh token
   * 
   * @param string|null $refreshToken
   * @return array
   */
  public function refreshToken(string|null $refreshToken): array
  {
    $payload = JsonWebToken::decode($refreshToken, env('REFRESH_TOKEN_SECRET'), true);

    // Check refresh token had exited in black list
    $key = CommonVal::BLACKLIST . ':' . Admin::TYPE . ':' . $payload['signature'];
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
      'access_token'  => $accessToken,
      'refresh_token' => $refreshToken
    ];
  }

  /**
   * Logout admin account
   *
   * @param string|null $refreshToken
   * @return array
   */
  public function logout(string|null $refreshToken): array
  {
    $payload = JsonWebToken::decode($refreshToken, env('REFRESH_TOKEN_SECRET'), true);
    $body = $payload['body'];

    $key = CommonVal::BLACKLIST . ':' . Admin::TYPE . ':' . $payload['signature'];

    if (Redis::hget($key, 'id')) { // Check already exit in blacklist
      throw new AuthorizationException(Messages::E0609, CommonVal::HTTP_UNAUTHORIZED);
    } else { // Create token and set expired time in blacklist
      Redis::hmset($key, $body);
      Redis::expireat($key, $body['exp']);
    }

    return [];
  }

  /**
   * Delete account
   * 
   * @param array $payload
   * @return bool
   */
  public function delete(array $payload): bool
  {
    if (!is_numeric($payload['id'])) {
      $message = Messages::getMessage(
        Messages::E0001,
        ['attributes' => Admin::attributes()['id']]
      );
      throw new InvalidArgumentException($message, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    // Only root can delete account
    if (!RoleRepository::isRoot($payload["admin_id"])) {
      throw new AuthorizationException(Messages::E0403, CommonVal::HTTP_FORBIDDEN);
    }

    AdminRepository::delete($payload['id']);

    return true;
  }
}
