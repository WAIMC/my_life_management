<?php

namespace App\Services\master;

use LogicException;
use App\Constants\Messages;
use App\constants\CommonVal;
use App\Services\CommonService;
use App\Services\SingletonService;
use App\Http\Resources\master\ApiRoleResource;
use App\Repositories\master\ApiRoleRepository;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\master\apiRole\ApiRoleListRequest;
use App\Http\Requests\master\apiRole\ApiRoleUpdateRequest;
use App\Repositories\master\RoleRepository;

class ApiRoleService extends SingletonService
{
  /**
   * Get api role list
   * 
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new ApiRoleListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = ApiRoleRepository::list($payload);

    return $list
      ? ApiRoleResource::collection($list)
      : [];
  }

  /**
   * Update api role
   * 
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    // Validation payload
    $validator = (new CommonService())->validationManual(
      (new ApiRoleUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    // TODO: Check current request role edit another role
    // Tip : create parent role and child role

    // Don't allow editing of personal role without role admin
    if (
      !RoleRepository::isRoot($payload["admin_id"])
      && ApiRoleRepository::isMyRole($payload)
    ) {
      throw new LogicException(Messages::E0018, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    // Insert api role
    if ($payload['insert']) {
      ApiRoleRepository::store($payload['insert']);
    }

    // Delete api role
    if ($payload['delete']) {
      ApiRoleRepository::delete($payload['delete']);
    }

    return true;
  }
}
