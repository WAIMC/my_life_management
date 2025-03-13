<?php

namespace App\Services\master;

use App\Constants\Messages;
use App\constants\CommonVal;
use App\Models\master\Skill;
use InvalidArgumentException;
use App\Services\CommonService;
use App\Services\SingletonService;
use App\Http\Resources\master\SkillResource;
use App\Repositories\master\SkillRepository;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\master\skill\SkillListRequest;
use App\Http\Requests\master\skill\SkillStoreRequest;
use App\Http\Requests\master\skill\SkillUpdateRequest;

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
