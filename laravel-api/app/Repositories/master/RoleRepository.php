<?php

namespace App\Repositories\master;

use DateTime;
use LogicException;
use App\Utilities\Tmp;
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
   * @return void
   */
  public static function store(array $payload): void
  {
    $fields = ["name", "permission", "is_active"];
    Role::create(Tmp::twoWayBindingByFields($fields, $payload));
  }

  /**
   * Update role
   * 
   * @param array $payload
   * @return void
   */
  public static function update(array $payload): void
  {
    $role = Role::find($payload['id']);
    if (!$role) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    $fields = ["name", "permission", "is_active"];
    $role->update(Tmp::twoWayBindingByFields($fields, $payload));
  }

  /**
   * Delete Role
   * 
   * @param string $id
   * @return void
   */
  public static function delete(string $id): void
  {
    $role = Role::find($id);
    if (!$role) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    $queryOne = DB::table('t_role AS tr')
      ->join('t_admin_role AS tar', 'tar.role_id', '=', 'tr.id')
      ->select(['tr.id AS id']);
    $queryTwo = DB::table('t_role AS tr2')
      ->join('t_api_role AS tar2', 'tar2.role_id', '=', 'tr2.id')
      ->select(['tr2.id AS id']);

    // Check Fk constraint violation
    $query = DB::query()
      ->from($queryOne->union($queryTwo), 'temp')
      ->select(['temp.id'])
      ->where('temp.id', $id)
      ->exists();

    if ($query) {
      throw new LogicException(Messages::E0016, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    $role->delete();
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
