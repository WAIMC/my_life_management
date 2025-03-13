<?php

namespace App\Repositories\master;

use DateTime;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Models\master\ApiRole;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ApiRoleRepository
{
  /**
   * Get api role list
   * 
   * @param array @payload
   * @return Collection
   */
  public static function list(array $payload): Collection
  {
    $query = DB::table('t_api_role AS tar')
      ->join('t_api AS ta', 'ta.id', '=', 'tar.api_id')
      ->join('t_role AS tr', 'tr.id', '=', 'tar.role_id')
      ->select([
        'tar.api_id     AS api_id',
        DB::raw("
          CASE
            WHEN ta.type = 0 THEN 'GET'
            WHEN ta.type = 1 THEN 'POST'
            WHEN ta.type = 2 THEN 'PUT'
            WHEN ta.type = 3 THEN 'PATCH'
            WHEN ta.type = 4 THEN 'DELETE'
            ELSE null
          END           AS type_name
        "),
        'ta.type        AS type',
        'ta.name        AS name',
        'ta.path        AS path',
        'ta.is_active   AS is_active',
        'tar.role_id    AS role_id',
        'tr.name        AS role_name',
        'tr.permission  AS role_permission',
        'tr.is_active   AS role_status',
        'tar.updated_at AS updated_at'
      ]);

    if (isset($payload['api_id'])) {
      $query->where('tar.api_id', $payload['api_id']);
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
   * Store api role
   * 
   * @param array $payload
   * @return void
   */
  public static function store(array $payload): void
  {
    // Don't allow insert data has exited
    if (self::isExistApiRole($payload)) {
      $attribute = ApiRole::attributes();
      throw new LogicException(
        Messages::getMessage(
          Messages::E0008,
          ['attributes' => $attribute['api_id'] . ', ' . $attribute['role_id']]
        ),
        CommonVal::HTTP_UNPROCESSABLE_CONTENT
      );
    }

    $values = [];
    foreach ($payload as $key => $val) {
      $values[$key]['api_id'] = $val['api_id'];
      $values[$key]['role_id'] = $val['role_id'];
      $values[$key]['created_at'] = Carbon::now();
      $values[$key]['updated_at'] = Carbon::now();
    }

    ApiRole::insert($values);
  }

  /**
   * Delete api role
   * 
   * @param array $payload
   * @return void
   */
  public static function delete(array $payload): void
  {
    $values = [];
    foreach ($payload as $val) {
      $values[] = "(" . $val['api_id'] . ", " . $val['role_id'] . ")";
    }

    ApiRole::whereRaw("(api_id, role_id) IN (" . implode(", ", $values) . ")")
      ->delete();
  }

  /**
   * Check is my role
   * 
   * @param array $payload
   * @return bool
   */
  public static function isMyRole(array $payload): bool
  {
    $roleId = [];

    if ($payload['insert']) {
      foreach ($payload['insert'] as $val) {
        $roleId[] = '(' . $val['role_id'] . ')';
      }
    }

    if ($payload['delete']) {
      foreach ($payload['delete'] as $val) {
        $roleId[] = '(' . $val['role_id'] . ')';
      }
    }

    $subQuery = DB::raw("(VALUES " . implode(", ", $roleId) . " ) AS temp(role_id)");

    $query = DB::table('admin_permission_view AS apv')
      ->join($subQuery, 'temp.role_id', '=', 'apv.role_id')
      ->where('admin_id', $payload['admin_id']);

    return $query->exists();
  }

  /**
   * Is exist Api role
   * 
   * @param array $payload
   * @return bool
   */
  public static function isExistApiRole(array $payload): bool
  {
    $values = array_map(function (array $val) {
      return "(" . $val['api_id'] . ", " . $val['role_id'] . ")";
    }, $payload);

    return ApiRole::whereRaw("(api_id, role_id) IN (" . implode(", ", $values) . ")")
      ->exists();
  }
}
