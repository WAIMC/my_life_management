<?php

namespace App\Services\Master;

use App\Http\Requests\Master\DepartmentManagement\DepartmentManagementListRequest;
use App\Http\Requests\Master\DepartmentManagement\DepartmentManagementUpdateRequest;
use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use App\Repositories\Master\DepartmentManagementRepository;
use App\Http\Resources\Master\DepartmentManagementResource;

class DepartmentManagementService extends SingletonService
{
  /**
   * Get department management list
   *
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new DepartmentManagementListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = DepartmentManagementRepository::list($payload);

    return $list
      ? DepartmentManagementResource::collection($list)
      : [];
  }

  /**
   * Update department management
   *
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    // Validation payload
    $validator = (new CommonService())->validationManual(
      (new DepartmentManagementUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    // Insert department management
    if ($payload['insert']) {
      DepartmentManagementRepository::store($payload['insert']);
    }

    // Delete department management
    if ($payload['delete']) {
      DepartmentManagementRepository::delete($payload['delete']);
    }

    return true;
  }
}
