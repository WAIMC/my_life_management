<?php

namespace App\Repositories\master;

use DateTime;
use LogicException;
use App\Utilities\Tmp;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Models\master\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentRepository
{
  /**
   * Get department list
   * 
   * @param array @payload
   * @return Collection
   */
  public static function list(array $payload): Collection
  {
    $query = Department::query()
      ->select('id', 'code', 'name', 'status', 'updated_at');

    if (isset($payload['code'])) {
      $query->where('code', 'like', '%' . $payload['code'] . '%');
    }

    if (isset($payload['name'])) {
      $query->where('name', 'like', '%' . $payload['name'] . '%');
    }

    if (isset($payload['status'])) {
      $query->where('status', $payload['status']);
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
   * Store department
   * 
   * @param array $payload
   * @return void
   */
  public static function store(array $payload): void
  {
    $fields = ["code", "name", "status"];
    Department::create(Tmp::twoWayBindingByFields($fields, $payload));
  }

  /**
   * Update department
   * 
   * @param array $payload
   * @return void
   */
  public static function update(array $payload): void
  {
    $department = Department::find($payload['id']);
    if (!$department) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    $fields = ["name", "permission", "is_active"];
    $department->update(Tmp::twoWayBindingByFields($fields, $payload));
  }

  /**
   * Delete department
   * 
   * @param string $id
   * @return void
   */
  public static function delete(string $id): void
  {
    $department = Department::find($id);
    if (!$department) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    $queryOne = DB::table('t_department AS td')
      ->join('t_admin_department AS tad', 'tad.department_id', '=', 'td.id')
      ->select(['td.id AS id']);
    $queryTwo = DB::table('t_department AS td2')
      ->join('t_department_management AS tam', 'tam.department_id', '=', 'td2.id')
      ->select(['td2.id AS id']);

    // Check Fk constraint violation
    $query = DB::query()
      ->from($queryOne->union($queryTwo), 'temp')
      ->select(['temp.id'])
      ->where('temp.id', $id)
      ->exists();

    if ($query) {
      throw new LogicException(Messages::E0016, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    $department->delete();
  }
}
