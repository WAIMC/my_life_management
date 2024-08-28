<?php

namespace App\Repositories\master;

use DateTime;
use App\Constants\Messages;
use App\Models\master\Role;
use App\Constants\CommonVal;
use App\Models\master\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleRepository
{
  /**
   * Get role list
   * 
   * @param array @payload
   * @return Collection
   */
  public static function list(array $payload): Collection
  {
    $query = Role::query()
      ->select('id', 'name', 'permission', 'is_active', 'updated_at');

    if (isset($payload['name'])) {
      $query->where('name', 'like', '%' . $payload['name'] . '%');
    }

    if (isset($payload['permission'])) {
      $query->where('permission', 'like', '%' . $payload['permission'] . '%');
    }

    if (isset($payload['is_active'])) {
      $query->where('is_active', $payload['is_active']);
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
   * Store role
   * 
   * @param array $payload
   * @return bool
   */
  public static function store(array $payload): bool
  {
    $fields = ["name", "permission", "is_active"];
    $data = [];

    foreach ($fields as $value) {
      if (isset($payload[$value])) $data[$value] = $payload[$value];
    }

    Role::create($data);

    return true;
  }

  /**
   * Update role
   * 
   * @param array $payload
   * @return bool
   */
  public static function update(array $payload): bool
  {
    $role = Role::find($payload['id']);
    if (!$role->toArray()) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    $fields = ["name", "permission", "is_active"];

    foreach ($fields as $value) {
      if (isset($payload[$value])) $role->$value = $payload[$value];
    }
    $role->save();

    return true;
  }

  /**
   * Delete Role
   * 
   * @param string $id
   * @return bool
   */
  public static function delete(string $id): bool
  {
    $role = Role::find($id);
    if (!$role->toArray()) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    $role->delete();

    return true;
  }

  /**
   * Check is root
   * 
   * @param int $id
   * @return bool
   */
  public static function isRoot(int $id): bool
  {
    return DB::table('admin_permission_view AS apv')
      ->where('admin_id', $id)
      ->where('role_name', Admin::ROOT)
      ->exists();
  }
}
