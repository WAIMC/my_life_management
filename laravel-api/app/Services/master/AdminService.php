<?php

namespace App\Services\master;

use App\Constants\Messages;
use App\constants\CommonVal;
use App\Models\master\Admin;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Utilities\JsonWebToken;
use App\Services\SingletonService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\master\admin\AdminRegisterRequest;

use function PHPSTORM_META\type;

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
    $validator = (new CommonService())->validationManual(
      (new AdminRegisterRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $this->createOrUpdateAccount($payload);

    return true;
  }

  /**
   * Create or update account admin
   * 
   * @param array $payload
   * @return void
   */
  public function createOrUpdateAccount(array $payload): void
  {
    $secondArray = [];

    foreach ($payload as $index => $value) {
      if (isset(Admin::attributes()[$index])) {
        if ($index === 'password') {
          $secondArray[$index] = bcrypt($value);
        } else {
          $secondArray[$index] = $value;
        }
      }
    }

    /**
     * If first array not exist in admin table => create new second array 
     * If first array exist in admin table => update second array
     */
    Admin::updateOrCreate(
      ['email' => $payload['email']],
      $secondArray
    );
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
