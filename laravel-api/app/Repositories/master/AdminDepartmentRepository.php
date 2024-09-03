<?php

namespace App\Repositories\master;

use DateTime;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Models\master\AdminDepartment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminDepartmentRepository
{
  /**
   * Get admin department list
   * 
   * @param array @payload
   * @return Collection
   */
  public static function list(array $payload): Collection
  {
    $query = DB::table('t_admin_department AS tad')
      ->join('t_admin AS ta', 'ta.id', '=', 'tad.admin_id')
      ->join('t_department AS td', 'td.id', '=', 'tad.department_id')
      ->select([
        'tad.admin_id      AS admin_id',
        'ta.email          AS email',
        'ta.status         AS admin_status',
        'ta.is_active      AS is_active',
        'tad.department_id AS department_id',
        'td.code           AS department_code',
        'td.name           AS department_name',
        'td.status         AS department_status',
        'tad.updated_at    AS updated_at'
      ]);

    if (isset($payload['admin_id'])) {
      $query->where('tad.admin_id', $payload['admin_id']);
    }

    if (isset($payload['department_id'])) {
      $query->where('tad.department_id', $payload['department_id']);
    }

    if (isset($payload['from_date'])) {
      $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
      $query->whereDate('tad.updated_at', '>=', $fromDate);
    }

    if (isset($payload['to_date'])) {
      $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
      $query->whereDate('tad.updated_at', '<=', $toDate);
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
    // TODO::Research case admin inactive

    // Don't allow insert data has exited
    if (self::isExistAdminDepartment($payload)) {
      $attribute = AdminDepartment::attributes();
      throw new LogicException(
        Messages::getMessage(
          Messages::E0008,
          ['attributes' => $attribute['admin_id'] . ', ' . $attribute['department_id']]
        ),
        CommonVal::HTTP_UNPROCESSABLE_CONTENT
      );
    }

    $values = [];
    foreach ($payload as $key => $val) {
      $values[$key]['admin_id'] = $val['admin_id'];
      $values[$key]['department_id'] = $val['department_id'];
      $values[$key]['created_at'] = Carbon::now();
      $values[$key]['updated_at'] = Carbon::now();
    }

    AdminDepartment::insert($values);
  }

  /**
   * Delete admin department
   * 
   * @param array $payload
   * @return void
   */
  public static function delete(array $payload): void
  {
    $values = [];
    foreach ($payload as $val) {
      $values[] = "(" . $val['admin_id'] . ", " . $val['department_id'] . ")";
    }

    AdminDepartment::whereRaw("(admin_id, department_id) IN (" . implode(", ", $values) . ")")
      ->delete();
  }

  /**
   * Is exist admin department
   * 
   * @param array $payload
   * @return bool
   */
  public static function isExistAdminDepartment(array $payload): bool
  {
    $values = array_map(function (array $val) {
      return "(" . $val['admin_id'] . ", " . $val['department_id'] . ")";
    }, $payload);

    return AdminDepartment::whereRaw("(admin_id, department_id) IN (" . implode(", ", $values) . ")")
      ->exists();
  }
}
