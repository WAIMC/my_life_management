<?php

namespace App\Services\Master;

use App\Services\BaseJunctionService;
use App\Interfaces\Master\AdminRoleMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Master\AdminRoleMstResource;

class AdminRoleMstService extends BaseJunctionService
{
  public function __construct(
    protected AdminRoleMstInterface $adminRoleMst
  ) {}

  /**
   * Get admin role mst list
   *
   * @param array $payload
   * @return JsonResource
   */
  public function list(array $payload): JsonResource
  {
    $list = $this->adminRoleMst->list($payload);

    return AdminRoleMstResource::collection($list);
  }

  /**
   * Update admin role mst
   *
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    // Don't allow editing of personal role without role admin
    if ($this->adminRoleMst->isMyRole($payload)) {
      throw new LogicException(Messages::E0018, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    // Delete admin role mst
    if (isset($payload['delete']) && $payload['delete']) {
      $this->validateExistence(
        $payload['delete'],
        fn($values) => $this->adminRoleMst->getAdminRoleMstId($values),
        'admin_role_id',
        'admin_role_mst'
      );
      $this->adminRoleMst->executeDelete($payload['delete']);
    }

    // Insert admin role mst
    if (isset($payload['insert']) && $payload['insert']) {
      $this->validateNonExistence(
        $payload['insert'],
        fn($values) => $this->adminRoleMst->getAdminRoleMstId($values),
        'admin_role_id',
        'admin_role_mst'
      );
      $this->adminRoleMst->executeStore($payload['insert']);
    }

    return true;
  }
}
