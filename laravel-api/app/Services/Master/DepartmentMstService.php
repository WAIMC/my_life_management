<?php

namespace App\Services\Master;

use App\Constants\Messages;
use App\Models\Master\RoleMst;
use App\Constants\CommonVal;
use InvalidArgumentException;
use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\Master\DepartmentResource;
use App\Repositories\Master\DepartmentMstRepository;
use App\Http\Requests\Master\Department\DepartmentMstListRequest;
use App\Http\Requests\Master\Department\DepartmentMstStoreRequest;
use App\Http\Requests\Master\Department\DepartmentMstUpdateRequest;
use App\Models\Master\DepartmentMst;

class DepartmentMstService extends SingletonService
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
      (new DepartmentMstListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = DepartmentMstRepository::list($payload);

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
      (new DepartmentMstStoreRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }
    DepartmentMstRepository::store($payload);

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
      (new DepartmentMstUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    DepartmentMstRepository::update($payload);

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
        ['attributes' => DepartmentMst::attributes()['id']]
      );
      throw new InvalidArgumentException($message, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    DepartmentMstRepository::delete($id);

    return true;
  }
}
