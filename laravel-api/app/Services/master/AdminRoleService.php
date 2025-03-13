<?php

namespace App\Services\master;

use LogicException;
use App\Constants\Messages;
use App\constants\CommonVal;
use App\Services\CommonService;
use App\Services\SingletonService;
use App\Repositories\master\RoleRepository;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\master\AdminRoleResource;
use App\Repositories\master\AdminRoleRepository;
use App\Http\Requests\master\adminRole\AdminRoleListRequest;
use App\Http\Requests\master\adminRole\AdminRoleUpdateRequest;

class AdminRoleService extends SingletonService
{
  /**
   * Get admin role list
   * 
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new AdminRoleListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = AdminRoleRepository::list($payload);

    return $list
      ? AdminRoleResource::collection($list)
      : [];
  }

  /**
   * Update admin role
   * 
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    // Validation payload
    $validator = (new CommonService())->validationManual(
      (new AdminRoleUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    // Don't allow editing of personal role without role admin
    if (!RoleRepository::isRoot($payload["admin_id"])) {
      throw new LogicException(Messages::E0018, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    // Insert api role
    if ($payload['insert']) {
      AdminRoleRepository::store($payload['insert']);
    }

    // Delete api role
    if ($payload['delete']) {
      AdminRoleRepository::delete($payload['delete']);
    }

    return true;
  }
}
