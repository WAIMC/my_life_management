<?php

namespace App\Repositories\master;

use DateTime;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Models\master\Feature;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use LogicException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FeatureRepository
{
  /**
   * Get feature list
   * 
   * @param array @payload
   * @return Collection
   */
  public static function list(array $payload): Collection
  {
    $query = Feature::query()
      ->select('id', 'name', 'group_name', 'status', 'updated_at');

    if (isset($payload['name'])) {
      $query->where('name', 'like', '%' . $payload['name'] . '%');
    }

    if (isset($payload['group_name'])) {
      $query->where('group_name', 'like', '%' . $payload['group_name'] . '%');
    }

    if (isset($payload['status'])) {
      $query->where('status', $payload['status']);
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
   * Store api
   * 
   * @param array $payload
   * @return bool
   */
  public static function store(array $payload): bool
  {
    Feature::create([
      'name' => $payload['name'],
      'group_name' => $payload['group_name'],
      'description' => $payload['description'],
      'status' => $payload['status'],
    ]);

    return true;
  }

  /**
   * Update feature
   * 
   * @param array $payload
   * @return bool
   */
  public static function update(array $payload): bool
  {
    $feature = Feature::find($payload['id']);
    if (!$feature) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    $feature->name = $payload['name'];
    $feature->group_name = $payload['group_name'];
    $feature->description = $payload['description'];
    $feature->status = $payload['status'];
    $feature->save();

    return true;
  }

  /**
   * Delete feature
   * 
   * @param string $id
   * @return bool
   */
  public static function delete(string $id): bool
  {
    // Check feature exits in db
    $feature = Feature::find($id);
    if (!$feature->toArray()) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    // Check Fk constraint violation
    $api = DB::table('t_feature AS tf')
      ->join('t_api AS ta', 'ta.feature_id', '=', 'tf.id')
      ->select('tf.id')
      ->where('tf.id', $id)
      ->get();

    if ($api->toArray()) {
      throw new LogicException(Messages::E0016, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    $feature->delete();

    return true;
  }
}
