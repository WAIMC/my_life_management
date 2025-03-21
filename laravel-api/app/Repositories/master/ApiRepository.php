<?php

namespace App\Repositories\master;

use DateTime;
use LogicException;
use App\Utilities\Tmp;
use App\Models\master\Api;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Models\master\Feature;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiRepository
{
  /**
   * Get api list
   * 
   * @param array @payload
   * @return Collection
   */
  public static function list(array $payload): Collection
  {
    $query = DB::table('t_api AS ta')
      ->join('t_feature AS tf', 'tf.id', '=', 'ta.feature_id')
      ->select([
        'ta.id         AS id',
        DB::raw("
          CASE
            WHEN ta.type = 0 THEN 'GET'
            WHEN ta.type = 1 THEN 'POST'
            WHEN ta.type = 2 THEN 'PUT'
            WHEN ta.type = 3 THEN 'PATCH'
            WHEN ta.type = 4 THEN 'DELETE'
            ELSE null
          END          AS type_name
        "),
        'ta.type       AS type',
        'ta.name       AS name',
        'ta.path       AS path',
        'ta.is_active  AS is_active',
        'ta.feature_id AS feature_id',
        'tf.name       AS feature_name',
        'tf.group_name AS feature_group',
        'ta.updated_at AS updated_at'
      ]);

    if (isset($payload['type'])) {
      $query->where('ta.type', $payload['type']);
    }

    if (isset($payload['name'])) {
      $query->where('ta.name', 'like', '%' . $payload['name'] . '%');
    }

    if (isset($payload['path'])) {
      $query->where('ta.path', 'like', '%' . $payload['path'] . '%');
    }

    if (isset($payload['is_active'])) {
      $query->where('ta.is_active', $payload['is_active']);
    }

    if (isset($payload['feature_id'])) {
      $query->where('ta.feature_id', $payload['feature_id']);
    }

    if (isset($payload['from_date'])) {
      $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
      $query->whereDate('ta.updated_at', '>=', $fromDate);
    }

    if (isset($payload['to_date'])) {
      $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
      $query->whereDate('ta.updated_at', '<=', $toDate);
    }

    $query->where('tf.status', Feature::FEATURE_STATUS['active']);

    return $query->get();
  }

  /**
   * Store api
   * 
   * @param array $payload
   * @return void
   */
  public static function store(array $payload): void
  {
    $fields = ['type', 'name', 'path', 'is_active', 'feature_id'];
    Api::create(Tmp::twoWayBindingByFields($fields, $payload));
  }

  /**
   * Update api
   * 
   * @param array $payload
   * @return void
   */
  public static function update(array $payload): void
  {
    $api = Api::find($payload['id']);
    if (!$api) {
      throw new NotFoundHttpException(Messages::E0404);
    }
    $fields = ['type', 'name', 'path', 'is_active', 'feature_id'];
    $api->update(Tmp::twoWayBindingByFields($fields, $payload));
  }

  /**
   * Delete api
   * 
   * @param string $id
   * @return void
   */
  public static function delete(string $id): void
  {
    // Check api exits in db
    $api = Api::find($id);
    if (!$api) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    // Check Fk constraint violation
    $apiRole = DB::table('t_api AS ta')
      ->join('t_api_role AS tar', 'tar.api_id', '=', 'ta.id')
      ->select('ta.id')
      ->where('ta.id', $id)
      ->exists();

    if ($apiRole) {
      throw new LogicException(Messages::E0016, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    $api->delete();
  }
}
