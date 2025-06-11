<?php

namespace App\Services\Master;

use App\Constants\Messages;
use App\Constants\CommonVal;
use InvalidArgumentException;
use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Master\PolicyDepartment\PolicyDepartmentListRequest;
use App\Repositories\Master\PolicyDepartmentRepository;
use App\Http\Requests\Master\PolicyDepartment\PolicyDepartmentStoreRequest;
use App\Http\Requests\Master\PolicyDepartment\PolicyDepartmentUpdateRequest;
use App\Http\Resources\Master\PolicyDepartmentResource;
use App\Models\Master\PolicyDepartment;

class PolicyDepartmentService extends SingletonService
{
  /**
   * Get policy department list
   *
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new PolicyDepartmentListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = PolicyDepartmentRepository::list($payload);

    return $list
      ? PolicyDepartmentResource::collection($list)
      : [];
  }

  /**
   * Store policy department
   *
   * @param array $payload
   * @return bool
   */
  public function store(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new PolicyDepartmentStoreRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    PolicyDepartmentRepository::store($payload);

    return true;
  }

  /**
   * Update policy department
   *
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new PolicyDepartmentUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    PolicyDepartmentRepository::update($payload);

    return true;
  }

  /**
   * Delete policy department
   *
   * @param string $id
   * @return bool
   */
  public function delete(string $id): bool
  {
    if (!is_numeric($id)) {
      $message = Messages::getMessage(
        Messages::E0001,
        ['attributes' => PolicyDepartment::attributes()['id']]
      );
      throw new InvalidArgumentException($message, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    PolicyDepartmentRepository::delete($id);

    return true;
  }
}
