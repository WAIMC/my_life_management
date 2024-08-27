<?php

namespace App\Services\master;

use App\constants\CommonVal;
use App\Constants\Messages;
use App\Http\Requests\master\feature\FeatureListRequest;
use App\Http\Requests\master\feature\FeatureStoreRequest;
use App\Http\Requests\master\feature\FeatureUpdateRequest;
use InvalidArgumentException;
use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\master\FeatureResource;
use App\Models\master\Feature;
use App\Repositories\master\FeatureRepository;

class FeatureService extends SingletonService
{
  /**
   * Get Feature list
   * 
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new FeatureListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = FeatureRepository::list($payload);

    return $list
      ? FeatureResource::collection($list)
      : [];
  }

  /**
   * Store feature
   * 
   * @param array $payload
   * @return bool
   */
  public function store(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new FeatureStoreRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    FeatureRepository::store($payload);

    return true;
  }

  /**
   * Update feature
   * 
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new FeatureUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    FeatureRepository::update($payload);

    return true;
  }

  /**
   * Delete feature
   * 
   * @param string $id
   * @return bool
   */
  public function delete(string $id): bool
  {
    if (!is_numeric($id)) {
      $message = Messages::getMessage(
        Messages::E0001,
        ['attributes' => Feature::attributes()['id']]
      );
      throw new InvalidArgumentException($message, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    FeatureRepository::delete($id);

    return true;
  }
}
