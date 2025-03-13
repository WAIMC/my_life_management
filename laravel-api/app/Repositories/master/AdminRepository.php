<?php

namespace App\Repositories\master;

use DateTime;
use App\Constants\Messages;
use App\constants\CommonVal;
use App\Models\master\Admin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminRepository
{
  /**
   * Get account list
   * 
   * @param array $payload
   * @return \Illuminate\Support\Collection
   */
  public static function list(array $payload): Collection
  {
    $query = DB::table("t_admin")
      ->select([
        'id',
        'email',
        'user_name',
        'first_name',
        'last_name',
        'address',
        'phone_number',
        'birth',
        'gender',
        'status',
        'is_active',
        'avatar',
        'updated_at',
      ]);

    if (isset($payload['email'])) {
      $query->where('email', $payload['email']);
    }

    if (isset($payload['user_name'])) {
      $query->where('user_name', $payload['user_name']);
    }

    if (isset($payload['first_name'])) {
      $query->where('first_name', 'like', '%' . $payload['first_name'] . '%');
    }

    if (isset($payload['last_name'])) {
      $query->where('last_name', 'like', '%' . $payload['last_name'] . '%');
    }

    if (isset($payload['address'])) {
      $query->where('address', 'like', '%' . $payload['address'] . '%');
    }

    if (isset($payload['phone_number'])) {
      $query->where('phone_number', $payload['phone_number']);
    }

    if (isset($payload['birth'])) {
      $query->where('birth', $payload['birth']);
    }

    if (isset($payload['gender'])) {
      $query->where('gender', $payload['gender']);
    }

    if (isset($payload['status'])) {
      $query->where('status', $payload['status']);
    }

    if (isset($payload['is_active'])) {
      $query->where('is_active', $payload['is_active']);
    }

    if (isset($payload['avatar'])) {
      $query->where('avatar', $payload['avatar']);
    }

    if (isset($payload['from_date'])) {
      $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
      $query->whereDate('updated_at', '>=', $fromDate);
    }

    if (isset($payload['to_date'])) {
      $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
      $query->whereDate('updated_at', '<=', $toDate);
    }

    return $query->get();
  }

  /**
   * Create new admin account
   * @param array $payload
   * @return void
   */
  public static function store(array $payload): void
  {
    $data = [];
    foreach ($payload as $index => $value) {
      if (isset(Admin::attributes()[$index])) {
        $data[$index] = ($index === 'password')
          ? bcrypt($value)
          : $value;
      }
    }

    Admin::Create($data);
  }


  /**
   * Update admin
   * 
   * @param array $payload
   * @return void
   */
  public static function update(array $payload): void
  {
    $admin = Admin::where('id', $payload['id'])->first();
    if (!$admin) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    // Don't allow editing if not personal role or role root
    if (
      !RoleRepository::isRoot($payload["admin_id"])
      && ($admin && $admin->id !== $payload['admin_id'])
    ) {
      throw new AuthorizationException(Messages::E0403, CommonVal::HTTP_FORBIDDEN);
    }

    $fields = [
      "password"     => fn ($value) => bcrypt($value),
      "first_name"   => null,
      "last_name"    => null,
      "address"      => null,
      "phone_number" => null,
      "birth"        => null,
      "gender"       => null,
      "status"       => null,
      "is_active"    => null,
      "avatar"       => null,
    ];

    foreach ($fields as $key => $callBack) {
      if (isset($payload[$key])) {
        $admin->$key = $callBack ? $callBack($payload[$key]) : $payload[$key];
      }
    }

    $admin->save();
  }


  /**
   * Delete Role
   * 
   * @param string $id
   * @return void
   */
  public static function delete(string $id): void
  {
    $admin = Admin::find($id);
    if (!$admin->toArray()) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    $admin->delete();
  }

  /**
   * Check is admin active
   * 
   * @param array $data
   * @return bool
   */
  public static function isAdminActive(array $data): bool
  {
    return Admin::whereIn('id', $data)
      ->where('is_active', Admin::IS_ACTIVE['enable'])
      ->exists();
  }
}
