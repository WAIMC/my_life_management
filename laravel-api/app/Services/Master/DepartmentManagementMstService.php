<?php

namespace App\Services\Master;

use App\Services\BaseJunctionService;
use App\Interfaces\Master\DepartmentManagementMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Master\DepartmentManagementMstResource;

class DepartmentManagementMstService extends BaseJunctionService
{
  public function __construct(
    protected DepartmentManagementMstInterface $departmentManagementMst
  ) {}

  /**
   * Get department management mst list
   *
   * @param array $payload
   * @return JsonResource
   */
  public function list(array $payload): JsonResource
  {
    $list = $this->departmentManagementMst->list($payload);

    return DepartmentManagementMstResource::collection($list);
  }

  /**
   * Update department management mst
   *
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    // Delete department management mst
    if (!empty($payload['delete'])) {
      $this->validateExistence(
        $payload['delete'],
        fn($values) => $this->departmentManagementMst->getDepartmentManagementMstId($values),
        'department_management_id',
        'department_management_mst'
      );
      $this->departmentManagementMst->executeDelete($payload['delete']);
    }

    // Insert department management mst
    if (!empty($payload['insert'])) {
      $this->validateNonExistence(
        $payload['insert'],
        fn($values) => $this->departmentManagementMst->getDepartmentManagementMstId($values),
        'department_management_id',
        'department_management_mst'
      );
      $this->departmentManagementMst->executeStore($payload['insert']);
    }

    return true;
  }
}
