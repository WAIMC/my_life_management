<?php

namespace App\Services\master;

use App\Constants\Messages;
use App\constants\CommonVal;
use App\Models\master\Admin;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Utilities\JsonWebToken;
use function PHPSTORM_META\type;
use App\Services\SingletonService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

use App\Http\Requests\master\admin\AdminUpdateRequest;
use App\Http\Requests\master\admin\AdminRegisterRequest;
use App\Repositories\master\RoleRepository;

class AdminService extends SingletonService
{
  /**
   * Handle register account admin
   * 
   * @param array @payload
   * @return bool
   */
  public function register(array $payload): bool
  {
    // Validate
    $validator = (new CommonService())->validationManual(
      (new AdminRegisterRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    // Check is role admin root
    if (!RoleRepository::isRoot($payload["admin_id"])) {
      throw new AuthorizationException(Messages::E0403, CommonVal::HTTP_FORBIDDEN);
    }

    $data = [];

    foreach ($payload as $index => $value) {
      if (isset(Admin::attributes()[$index])) {
        $data[$index] = ($index === 'password')
          ? bcrypt($value)
          : $value;
      }
    }

    Admin::Create($data);

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

    $admin = Admin::where("id", $payload["admin_id"])
      ->where("email", $payload["email"])
      ->where("user_name", $payload["user_name"])
      ->where("is_active", Admin::$isActive['enable'])
      ->first();

    // Don't allow editing if not personal role or role root
    if (!RoleRepository::isRoot($payload["admin_id"]) && $admin) {
      throw new AuthorizationException(Messages::E0403, CommonVal::HTTP_FORBIDDEN);
    }

    if (isset($payload["password"])) {
      $admin->password = bcrypt($payload["password"]);
    }

    if (isset($payload["first_name"])) {
      $admin->first_name = $payload["first_name"];
    }

    if (isset($payload["last_name"])) {
      $admin->last_name = $payload["last_name"];
    }

    if (isset($payload["address"])) {
      $admin->address = $payload["address"];
    }

    if (isset($payload["phone_number"])) {
      $admin->phone_number = $payload["phone_number"];
    }

    if (isset($payload["birth"])) {
      $admin->birth = $payload["birth"];
    }

    if (isset($payload["gender"])) {
      $admin->gender = $payload["gender"];
    }

    if (isset($payload["status"])) {
      $admin->status = $payload["status"];
    }

    if (isset($payload["is_active"])) {
      $admin->is_active = $payload["is_active"];
    }

    if (isset($payload["avatar"])) {
      $admin->avatar = $payload["avatar"];
    }

    $admin->save();

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
   * @return []
   */
  public function logout(Request $request)
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
}
