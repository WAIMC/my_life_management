<?php

namespace App\Repositories\master;

use DateTime;
use LogicException;
use App\Utilities\Tmp;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Models\master\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryRepository
{
  /**
   * Category list
   * 
   * @param array @payload
   * @return Collection
   */
  public static function list(array $payload): Collection
  {
    $query = Category::query()
      ->select('id', 'parent_id', 'name', 'slug', 'description', 'status', 'is_display', 'rank_order', 'updated_at');

    if (isset($payload['parent_id'])) {
      $query->where('parent_id', $payload['parent_id']);
    }

    if (isset($payload['name'])) {
      $query->where('name', 'like', '%' . $payload['name'] . '%');
    }

    if (isset($payload['slug'])) {
      $query->where('slug', 'like', '%' . $payload['slug'] . '%');
    }

    if (isset($payload['description'])) {
      $query->where('description', 'like', '%' . $payload['description'] . '%');
    }

    if (isset($payload['status'])) {
      $query->where('status', $payload['status']);
    }

    if (isset($payload['is_display'])) {
      $query->where('is_display', $payload['is_display']);
    }

    if (isset($payload['rank_order'])) {
      $query->where('rank_order', $payload['rank_order']);
    }

    if (isset($payload['from_date'])) {
      $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
      $query->whereDate('updated_at', '>=', $fromDate);
    }

    if (isset($payload['to_date'])) {
      $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
      $query->whereDate('updated_at', '<=', $toDate);
    }

    return $query->get();
  }

  /**
   * Store category
   * 
   * @param array $payload
   * @return void
   */
  public static function store(array $payload): void
  {
    $fields = ['parent_id', 'name', 'slug', 'description', 'status', 'is_display', 'rank_order'];
    Category::create(Tmp::twoWayBindingByFields($fields, $payload));
  }

  /**
   * Update category
   * 
   * @param array $payload
   * @return void
   */
  public static function update(array $payload): void
  {
    $category = Category::find($payload['id']);
    if (!$category) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    $fields = ['parent_id', 'name', 'slug', 'description', 'status', 'is_display', 'rank_order'];
    $category->update(Tmp::twoWayBindingByFields($fields, $payload));
  }

  /**
   * Delete category
   * 
   * @param string $id
   * @return void
   */
  public static function delete(string $id): void
  {
    $category = Category::find($id);
    if (!$category) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    // Check Fk constraint violation
    $query = DB::table('t_category AS tc')
      ->join('t_category_skill AS tcs', 'tcs.category_id', '=', 'tc.id')
      ->select(['tc.id AS id'])
      ->exists();

    if ($query) {
      throw new LogicException(Messages::E0016, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    $category->delete();
  }
}
