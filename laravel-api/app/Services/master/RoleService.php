<?php

namespace App\Services\master;

use App\Http\Requests\role\RoleListRequest;
use App\Http\Resources\master\RoleResource;
use App\Repositories\master\RoleRepository;
use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;

class RoleService extends SingletonService
{
  /**
   * Get list roles
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

    $list = (new RoleRepository())->list($payload);

    return $list
      ? RoleResource::collection($list)
      : [];
  }
}
