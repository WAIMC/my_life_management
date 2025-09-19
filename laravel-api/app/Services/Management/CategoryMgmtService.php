<?php

namespace App\Services\Management;

use App\Constants\CommonVal;
use App\Constants\Messages;
use App\Http\Requests\Master\Category\CategoryMstListRequest;
use App\Http\Requests\Master\Category\CategoryMstStoreRequest;
use App\Http\Requests\Master\Category\CategoryMstUpdateRequest;
use App\Http\Resources\Master\CategoryResource;
use App\Models\Management\CategoryMgmt;
use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

//use App\Repositories\Master\CategoryMgmtRepository;

class CategoryMgmtService extends SingletonService
{
  /**
   * CategoryMgmt list
   *
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new CategoryMstListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

//    $list = CategoryMgmtRepository::list($payload);

    return $list
      ? CategoryResource::collection($list)
      : [];
  }

  /**
   * Store category
   *
   * @param array $payload
   * @return bool
   */
  public function store(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new CategoryMstStoreRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

//    CategoryMgmtRepository::store($payload);

    return true;
  }

  /**
   * Update category
   *
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new CategoryMstUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

//    CategoryMgmtRepository::update($payload);

    return true;
  }

  /**
   * Delete category
   *
   * @param string $id
   * @return bool
   */
  public function delete(string $id): bool
  {
    if (!is_numeric($id)) {
      $message = Messages::getMessage(
        Messages::E0001,
        ['attributes' => CategoryMgmt::attributes()['id']]
      );
      throw new InvalidArgumentException($message, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

//    CategoryMgmtRepository::delete($id);

    return true;
  }
}
