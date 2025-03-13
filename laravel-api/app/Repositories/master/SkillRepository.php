<?php

namespace App\Repositories\master;

use DateTime;
use LogicException;
use App\Utilities\Tmp;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Models\master\Skill;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SkillRepository
{
  /**
   * Skill list
   * 
   * @param array $payload
   * @return Collection
   */
  public static function list(array $payload): Collection
  {
    $query = Skill::query()
      ->select('id', 'parent_id', 'name', 'slug', 'status', 'is_display', 'rank_order', 'updated_at');

    if (isset($payload['parent_id'])) {
      $query->where('parent_id', $payload['parent_id']);
    }

    if (isset($payload['name'])) {
      $query->where('name', 'like', '%' . $payload['name'] . '%');
    }

    if (isset($payload['slug'])) {
      $query->where('slug', 'like', '%' . $payload['slug'] . '%');
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
   * Store skill
   * 
   * @param array $payload
   * @return void
   */
  public static function store(array $payload): void
  {
    $fields = ['parent_id', 'name', 'slug', 'status', 'is_display', 'rank_order'];
    Skill::create(Tmp::twoWayBindingByFields($fields, $payload));
  }

  /**
   * Update skill
   * 
   * @param array $payload
   * @return void
   */
  public static function update(array $payload): void
  {
    $skill = Skill::find($payload['id']);
    if (!$skill) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    $fields = ['parent_id', 'name', 'slug', 'status', 'is_display', 'rank_order'];
    $skill->update(Tmp::twoWayBindingByFields($fields, $payload));
  }

  /**
   * Delete skill
   * 
   * @param string $id
   * @return void
   */
  public static function delete(string $id): void
  {
    $skill = Skill::find($id);
    if (!$skill) {
      throw new NotFoundHttpException(Messages::E0404);
    }

    $queryOne = DB::table('t_skill AS ts')
      ->join('t_category_skill AS tcs', 'tcs.skill_id', '=', 'ts.id')
      ->select(['ts.id AS id']);
    $queryTwo = DB::table('t_skill AS ts2')
      ->join('t_skill_description AS tsd', 'tsd.skill_id', '=', 'ts2.id')
      ->select(['ts2.id AS id']);

    // Check Fk constraint violation
    $query = DB::query()
      ->from($queryOne->union($queryTwo), 'temp')
      ->select(['temp.id'])
      ->where('temp.id', $id)
      ->exists();

    if ($query) {
      throw new LogicException(Messages::E0016, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    $skill->delete();
  }
}
