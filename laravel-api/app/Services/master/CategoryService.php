<?php

namespace App\Services\master;

use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\master\CategoryResource;
use App\Http\Requests\master\category\CategoryListRequest;
use App\Repositories\master\CategoryRepository;

class CategoryService extends SingletonService
{
  /**
   * Get category list
   * 
   * @param array $payload
   * @return mixed
   */
  public function categoryList(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new CategoryListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = (new CategoryRepository())->categoryList($payload);

    return $list
      ? CategoryResource::collection($list)
      : [];
  }
}
