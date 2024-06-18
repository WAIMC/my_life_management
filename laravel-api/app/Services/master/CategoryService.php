<?php

namespace App\Services\master;

use Illuminate\Http\Request;
use App\Models\master\Category;
use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\master\CategoryResource;
use App\Http\Requests\master\category\CategoryListRequest;

class CategoryService extends SingletonService
{
  /**
   * @param Request $request
   */
  public function categoryList(Request $request)
  {
    $validator = (new CommonService())->validationManual(
      (new CategoryListRequest()),
      $request->all()
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    return CategoryResource::collection(Category::all());;
  }
}
