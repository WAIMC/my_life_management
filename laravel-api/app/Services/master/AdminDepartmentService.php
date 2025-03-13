<?php

namespace App\Services\master;

use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use App\Repositories\master\AdminDepartmentRepository;
use App\Http\Requests\master\adminDepartment\AdminDepartmentListRequest;
use App\Http\Requests\master\adminDepartment\AdminDepartmentUpdateRequest;
use App\Http\Resources\master\AdminDepartmentResource;

class AdminDepartmentService extends SingletonService
{
  /**
   * Get admin department list
   * 
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new AdminDepartmentListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = AdminDepartmentRepository::list($payload);

    return $list
      ? AdminDepartmentResource::collection($list)
      : [];
  }

  /**
   * Update admin department
   * 
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    // Validation payload
    $validator = (new CommonService())->validationManual(
      (new AdminDepartmentUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    // Insert admin department
    if ($payload['insert']) {
      AdminDepartmentRepository::store($payload['insert']);
    }

    // Delete admin department
    if ($payload['delete']) {
      AdminDepartmentRepository::delete($payload['delete']);
    }

    return true;
  }
}
