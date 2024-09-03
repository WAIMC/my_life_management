<?php

namespace App\Repositories\master;

use DateTime;
use LogicException;
use App\Utilities\Tmp;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Models\master\PolicyDepartment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PolicyDepartmentRepository
{
  /**
   * Get policy department list
   * 
   * @param array @payload
   * @return Collection
   */
  public static function list(array $payload): Collection
  {
    $query = DB::table('t_policy_department AS tpd')
      ->select([
        'tpd.id         AS id',
        'tpd.table_name AS table_name',
        'tpd.row_id     AS row_id',
        'tpd.updated_at AS updated_at'
      ]);

    if (isset($payload['table_name'])) {
      $query->where('tpd.table_name', $payload['table_name']);
    }

    if (isset($payload['row_id'])) {
      $query->where('tpd.row_id', $payload['row_id']);
    }

    if (isset($payload['from_date'])) {
      $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
      $query->whereDate('tpd.updated_at', '>=', $fromDate);
    }

    if (isset($payload['to_date'])) {
      $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
      $query->whereDate('tpd.updated_at', '<=', $toDate);
    }

    return $query->get();
  }

  /**
   * Store policy department
   * 
   * @param array $payload
   * @return void
   */
  public static function store(array $payload): void
  {
    $fields = ['table_name', 'row_id'];
    PolicyDepartment::create(Tmp::twoWayBindingByFields($fields, $payload));
  }

  /**
   * Update policy department
   * 
   * @param array $payload
   * @return void
   */
  public static function update(array $payload): void
  {
    $policyDepartment = PolicyDepartment::find($payload['id']);
    if (!$policyDepartment) {
      throw new NotFoundHttpException(Messages::E0404);
    }
    $fields = ['tale_name', 'row_id'];
    $policyDepartment->update(Tmp::twoWayBindingByFields($fields, $payload));
  }

  /**
   * Delete policy department
   * 
   * @param string $id
   * @return void
   */
  public static function delete(string $id): void
  {
    // Check policy department exits in db
    $policyDepartment = PolicyDepartment::find($id);
    if (!$policyDepartment) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    // Check Fk constraint violation
    $departmentManagement = DB::table('t_policy_department AS tpd')
      ->join('t_department_management AS tdm', 'tdm.policy_department_id', '=', 'tpd.id')
      ->select('tpd.id')
      ->where('tpd.id', $id)
      ->exists();

    if ($departmentManagement) {
      throw new LogicException(Messages::E0016, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    $policyDepartment->delete();
  }
}
