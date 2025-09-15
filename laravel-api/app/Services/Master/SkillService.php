<?php

namespace App\Services\Master;

use App\Constants\CommonVal;
use App\Constants\Messages;
use App\Http\Requests\Master\Skill\SkillListRequest;
use App\Http\Requests\Master\Skill\SkillStoreRequest;
use App\Http\Requests\Master\Skill\SkillUpdateRequest;
use App\Http\Resources\Master\SkillResource;
use App\Models\Management\Skill;
use App\Repositories\Management\SkillRepository;
use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class SkillService extends SingletonService
{
  /**
   * Skill list
   *
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new SkillListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = SkillRepository::list($payload);

    return $list
      ? SkillResource::collection($list)
      : [];
  }

  /**
   * Store skill
   *
   * @param array $payload
   * @return bool
   */
  public function store(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new SkillStoreRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    SkillRepository::store($payload);

    return true;
  }

  /**
   * Update skill
   *
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    $validator = (new CommonService())->validationManual(
      (new SkillUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    SkillRepository::update($payload);

    return true;
  }

  /**
   * Delete skill
   *
   * @param string $id
   * @return bool
   */
  public function delete(string $id): bool
  {
    if (!is_numeric($id)) {
      $message = Messages::getMessage(
        Messages::E0001,
        ['attributes' => Skill::attributes()['id']]
      );
      throw new InvalidArgumentException($message, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    SkillRepository::delete($id);

    return true;
  }
}
