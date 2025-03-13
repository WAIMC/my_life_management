<?php

namespace App\Repositories\master;

use DateTime;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Models\master\DepartmentManagement;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DepartmentManagementRepository
{
  /**
   * Get department management list
   * 
   * @param array @payload
   * @return Collection
   */
  public static function list(array $payload): Collection
  {
    $query = DB::table('t_department_management AS tdm')
      ->join('t_department AS td', 'td.id', '=', 'tdm.department_id')
      ->join('t_policy_department AS tpd', 'tpd.id', '=', 'tdm.policy_department_id')
      ->select([
        'tdm.department_id        AS department_id',
        'td.code                  AS code',
        'td.name                  AS name',
        'td.status                AS department_status',
        'tdm.policy_department_id AS policy_department_id',
        'tpd.table_name           AS table_name',
        'tpd.row_id               AS row_id',
        'tdm.updated_at           AS updated_at'
      ]);

    if (isset($payload['department_id'])) {
      $query->where('tdm.department_id', $payload['department_id']);
    }

    if (isset($payload['policy_department_id'])) {
      $query->where('tdm.policy_department_id', $payload['policy_department_id']);
    }

    if (isset($payload['from_date'])) {
      $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
      $query->whereDate('tdm.updated_at', '>=', $fromDate);
    }

    if (isset($payload['to_date'])) {
      $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
      $query->whereDate('tdm.updated_at', '<=', $toDate);
    }

    return $query->get();
  }

  /**
   * Store department management
   * 
   * @param array $payload
   * @return void
   */
  public static function store(array $payload): void
  {
    // Don't allow insert data has exited
    if (self::isExistDepartmentManagement($payload)) {
      $attribute = DepartmentManagement::attributes();
      throw new LogicException(
        Messages::getMessage(
          Messages::E0008,
          ['attributes' => $attribute['department_id'] . ', ' . $attribute['policy_department_id']]
        ),
        CommonVal::HTTP_UNPROCESSABLE_CONTENT
      );
    }

    $values = [];
    foreach ($payload as $key => $val) {
      $values[$key]['department_id'] = $val['department_id'];
      $values[$key]['policy_department_id'] = $val['policy_department_id'];
      $values[$key]['created_at'] = Carbon::now();
      $values[$key]['updated_at'] = Carbon::now();
    }

    DepartmentManagement::insert($values);
  }

  /**
   * Delete department management
   * 
   * @param array $payload
   * @return void
   */
  public static function delete(array $payload): void
  {
    $values = [];
    foreach ($payload as $val) {
      $values[] = "(" . $val['department_id'] . ", " . $val['policy_department_id'] . ")";
    }

    DepartmentManagement::whereRaw("(department_id, policy_department_id) IN (" . implode(", ", $values) . ")")
      ->delete();
  }

  /**
   * Is exist department management
   * 
   * @param array $payload
   * @return bool
   */
  public static function isExistDepartmentManagement(array $payload): bool
  {
    $values = array_map(function (array $val) {
      return "(" . $val['department_id'] . ", " . $val['policy_department_id'] . ")";
    }, $payload);

    return DepartmentManagement::whereRaw("(department_id, policy_department_id) IN (" . implode(", ", $values) . ")")
      ->exists();
  }
}
