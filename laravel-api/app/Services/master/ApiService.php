<?php

namespace App\Services\master;

use App\Constants\Messages;
use App\constants\CommonVal;
use InvalidArgumentException;
use App\Services\CommonService;
use App\Services\SingletonService;
use App\Http\Resources\master\ApiResource;
use App\Repositories\master\ApiRepository;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\master\api\ApiListRequest;
use App\Http\Requests\master\api\ApiStoreRequest;
use App\Http\Requests\master\api\ApiUpdateRequest;
use App\Models\master\Api;

class ApiService extends SingletonService
{
  /**
   * Get api list
   * 
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new ApiListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = ApiRepository::list($payload);

    return $list
      ? ApiResource::collection($list)
      : [];
  }

  /**
   * Store api
   * 
   * @param array $payload
   * @return bool
   */
  public function store(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new ApiStoreRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    ApiRepository::store($payload);

    return true;
  }

  /**
   * Update api
   * 
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new ApiUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    ApiRepository::update($payload);

    return true;
  }

  /**
   * Delete api
   * 
   * @param string $id
   * @return bool
   */
  public function delete(string $id): bool
  {
    if (!is_numeric($id)) {
      $message = Messages::getMessage(
        Messages::E0001,
        ['attributes' => Api::attributes()['id']]
      );
      throw new InvalidArgumentException($message, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    ApiRepository::delete($id);

    return true;
  }
}
