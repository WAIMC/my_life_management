<?php

namespace App\Services\master;

use App\Constants\Messages;
use App\constants\CommonVal;
use InvalidArgumentException;
use App\Models\master\Category;
use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\master\CategoryResource;
use App\Repositories\master\CategoryRepository;
use App\Http\Requests\master\category\CategoryListRequest;
use App\Http\Requests\master\category\CategoryStoreRequest;
use App\Http\Requests\master\category\CategoryUpdateRequest;

class CategoryService extends SingletonService
{
  /**
   * Category list
   * 
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new CategoryListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = CategoryRepository::list($payload);

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
      (new CategoryStoreRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    CategoryRepository::store($payload);

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
      (new CategoryUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    CategoryRepository::update($payload);

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
        ['attributes' => Category::attributes()['id']]
      );
      throw new InvalidArgumentException($message, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    CategoryRepository::delete($id);

    return true;
  }
}
