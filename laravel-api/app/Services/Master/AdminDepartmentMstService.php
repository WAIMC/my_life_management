<?php

namespace App\Services\Master;

use App\Services\BaseJunctionService;
use App\Interfaces\Master\AdminDepartmentMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Master\AdminDepartmentMstResource;

class AdminDepartmentMstService extends BaseJunctionService
{
  public function __construct(
    protected AdminDepartmentMstInterface $adminDepartmentMst
  ) {}

  /**
   * Get admin department mst list
   *
   * @param array $payload
   * @return JsonResource
   */
  public function list(array $payload): JsonResource
  {
    $list = $this->adminDepartmentMst->list($payload);

    return AdminDepartmentMstResource::collection($list);
  }

  /**
   * Update admin department mst
   *
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    // Delete admin department mst
    if (!empty($payload['delete'])) {
      $this->validateExistence(
        $payload['delete'],
        fn($values) => $this->adminDepartmentMst->getAdminDepartmentMstId($values),
        'admin_department_id',
        'admin_department_mst'
      );
      $this->adminDepartmentMst->executeDelete($payload['delete']);
    }

    // Insert admin department mst
    if (!empty($payload['insert'])) {
      $this->validateNonExistence(
        $payload['insert'],
        fn($values) => $this->adminDepartmentMst->getAdminDepartmentMstId($values),
        'admin_department_id',
        'admin_department_mst'
      );
      $this->adminDepartmentMst->executeStore($payload['insert']);
    }

    return true;
  }
}
