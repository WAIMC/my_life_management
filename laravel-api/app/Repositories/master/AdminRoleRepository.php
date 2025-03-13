<?php

namespace App\Repositories\master;

use DateTime;
use Carbon\Carbon;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Models\master\Admin;
use App\Models\master\AdminRole;
use App\Models\master\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminRoleRepository
{
  /**
   * Get admin role list
   * 
   * @param array @payload
   * @return Collection
   */
  public static function list(array $payload): Collection
  {
    $query = DB::table('t_admin_role AS tar')
      ->join('t_admin AS ta', 'ta.id', '=', 'tar.admin_id')
      ->join('t_role AS tr', 'tr.id', '=', 'tar.role_id')
      ->select([
        'tar.admin_id   AS admin_id',
        'ta.email       AS email',
        'ta.first_name  AS first_name',
        'ta.last_name   AS last_name',
        'ta.status      AS status',
        'tar.role_id    AS role_id',
        'tr.name        AS role_name',
        'tr.permission  AS role_permission',
        'tar.updated_at AS updated_at'
      ])
      ->where('ta.is_active', Admin::IS_ACTIVE['enable'])
      ->where('tr.is_active', Role::IS_ACTIVE['enable']);

    if (isset($payload['admin_id'])) {
      $query->where('tar.admin_id', $payload['admin_id']);
    }

    if (isset($payload['role_id'])) {
      $query->where('tar.role_id', $payload['role_id']);
    }

    if (isset($payload['from_date'])) {
      $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
      $query->whereDate('tar.updated_at', '>=', $fromDate);
    }

    if (isset($payload['to_date'])) {
      $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
      $query->whereDate('tar.updated_at', '<=', $toDate);
    }

    return $query->get();
  }

  /**
   * Store admin role
   * 
   * @param array $payload
   * @return void
   */
  public static function store(array $payload): void
  {
    // Don't allow insert data has exited
    if (self::isExistAdminRole($payload)) {
      $attribute = AdminRole::attributes();
      throw new LogicException(
        Messages::getMessage(
          Messages::E0008,
          ['attributes' => $attribute['admin_id'] . ', ' . $attribute['role_id']]
        ),
        CommonVal::HTTP_UNPROCESSABLE_CONTENT
      );
    }

    $values = [];
    foreach ($payload as $key => $val) {
      $values[$key]['admin_id'] = $val['admin_id'];
      $values[$key]['role_id'] = $val['role_id'];
      $values[$key]['created_at'] = Carbon::now();
      $values[$key]['updated_at'] = Carbon::now();
    }

    AdminRole::insert($values);
  }

  /**
   * Delete admin role
   * 
   * @param array $payload
   * @return void
   */
  public static function delete(array $payload): void
  {
    $values = [];
    foreach ($payload as $val) {
      $values[] = "(" . $val['admin_id'] . ", " . $val['role_id'] . ")";
    }

    AdminRole::whereRaw("(admin_id, role_id) IN (" . implode(", ", $values) . ")")
      ->delete();
  }

  /**
   * Is exist admin role
   * 
   * @param array $payload
   * @return bool
   */
  public static function isExistAdminRole(array $payload): bool
  {
    $values = array_map(function (array $val) {
      return "(" . $val['admin_id'] . ", " . $val['role_id'] . ")";
    }, $payload);

    return AdminRole::whereRaw("(admin_id, role_id) IN (" . implode(", ", $values) . ")")
      ->exists();
  }
}
