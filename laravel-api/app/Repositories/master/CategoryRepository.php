<?php

namespace App\Repositories\master;

use DateTime;
use App\Constants\CommonVal;
use App\Models\master\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
  /**
   * Query get category list
   * 
   * @param array @payload
   * @return Collection
   */
  public function categoryList(array $payload): Collection
  {
    $query = Category::query();

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
}
