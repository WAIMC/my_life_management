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
   * @return string
   */
  public function login($payload): string
  {
    $admin = Admin::where('user_name', $payload['user_name'])->first();

    if (!$admin || !Hash::check($payload['password'], $admin->password)) {
      throw new AuthorizationException(Messages::E0401, CommonVal::HTTP_UNAUTHORIZED);
    }

    $payload = JsonWebToken::JWTPayload([
      'id' => (string)$admin->id,
      'type' => Admin::TYPE,
      'role' => 'admin'
    ]);

    // Create token and set expired time in redis
    $key = Admin::TYPE . ':' . $admin->id;
    Redis::hmset($key, $payload);
    Redis::expireat($key, $payload['exp']);

    return JsonWebToken::encode($payload, env('JWT_SECRET'));
  }

  /**
   * Logout admin account
   * 
   * @param Request $request
   * @return array
   */
  public function logout(Request $request): array
  {
    $adminId = $request->attributes->get('admin_id');

    // Delete token store in redis
    if (Redis::hget(Admin::TYPE . ':' . $adminId, 'id')) {
      Redis::del(Admin::TYPE . ':' . $adminId);
    }

    // Remove the Authorization header if set
    if ($request->header('Authorization')) {
      $request->headers->remove('Authorization');
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
