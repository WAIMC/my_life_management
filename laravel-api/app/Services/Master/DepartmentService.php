<?php

namespace App\Services\Master;

use App\Constants\Messages;
use App\Models\Master\Role;
use App\Constants\CommonVal;
use InvalidArgumentException;
use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\Master\DepartmentResource;
use App\Repositories\Master\DepartmentRepository;
use App\Http\Requests\Master\Department\DepartmentListRequest;
use App\Http\Requests\Master\Department\DepartmentStoreRequest;
use App\Http\Requests\Master\Department\DepartmentUpdateRequest;
use App\Models\Master\Department;

class DepartmentService extends SingletonService
{
  /**
   * Get department list
   *
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new DepartmentListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = DepartmentRepository::list($payload);

    return $list
      ? DepartmentResource::collection($list)
      : [];
  }

  /**
   * Store department
   *
   * @param array $payload
   * @return bool
   */
  public function store(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new DepartmentStoreRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }
    DepartmentRepository::store($payload);

    return true;
  }

  /**
   * Update department
   *
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new DepartmentUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    DepartmentRepository::update($payload);

    return true;
  }

  /**
   * Delete department
   *
   * @param string $id
   * @return bool
   */
  public function delete(string $id): bool
  {
    if (!is_numeric($id)) {
      $message = Messages::getMessage(
        Messages::E0001,
        ['attributes' => Department::attributes()['id']]
      );
      throw new InvalidArgumentException($message, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    DepartmentRepository::delete($id);

    return true;
  }
}
