<?php

namespace App\Services\master;

use App\constants\CommonVal;
use App\Constants\Messages;
use App\Models\master\Role;
use InvalidArgumentException;
use App\Services\CommonService;
use App\Services\SingletonService;
use App\Http\Resources\master\RoleResource;
use App\Repositories\master\RoleRepository;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\master\role\RoleListRequest;
use App\Http\Requests\master\role\RoleStoreRequest;
use App\Http\Requests\master\role\RoleUpdateRequest;

class RoleService extends SingletonService
{
  /**
   * Get role list
   * 
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new RoleListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = RoleRepository::list($payload);

    return $list
      ? RoleResource::collection($list)
      : [];
  }

  /**
   * Store role
   * 
   * @param array $payload
   * @return bool
   */
  public function store(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new RoleStoreRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    RoleRepository::store($payload);

    return true;
  }

  /**
   * Update role
   * 
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new RoleUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    RoleRepository::update($payload);

    return true;
  }

  /**
   * Delete role
   * 
   * @param string $id
   * @return bool
   */
  public function delete(string $id): bool
  {
    if (!is_numeric($id)) {
      $message = Messages::getMessage(
        Messages::E0001,
        ['attributes' => Role::attributes()['id']]
      );
      throw new InvalidArgumentException($message, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    RoleRepository::delete($id);

    return true;
  }
}
