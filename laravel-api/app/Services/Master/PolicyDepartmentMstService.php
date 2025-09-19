<?php

namespace App\Services\Master;

use App\Constants\Messages;
use App\Constants\CommonVal;
use InvalidArgumentException;
use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Master\PolicyDepartment\PolicyDepartmentMstListRequest;
use App\Repositories\Master\PolicyDepartmentMstRepository;
use App\Http\Requests\Master\PolicyDepartment\PolicyDepartmentMstStoreRequest;
use App\Http\Requests\Master\PolicyDepartment\PolicyDepartmentMstUpdateRequest;
use App\Http\Resources\Master\PolicyDepartmentResource;
use App\Models\Master\PolicyDepartmentMst;

class PolicyDepartmentMstService extends SingletonService
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
      (new PolicyDepartmentMstListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = PolicyDepartmentMstRepository::list($payload);

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
      (new PolicyDepartmentMstStoreRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    PolicyDepartmentMstRepository::store($payload);

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
      (new PolicyDepartmentMstUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    PolicyDepartmentMstRepository::update($payload);

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
        ['attributes' => PolicyDepartmentMst::attributes()['id']]
      );
      throw new InvalidArgumentException($message, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    PolicyDepartmentMstRepository::delete($id);

    return true;
  }
}
