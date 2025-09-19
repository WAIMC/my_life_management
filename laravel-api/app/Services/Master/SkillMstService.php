<?php

namespace App\Services\Master;

use App\Constants\CommonVal;
use App\Constants\Messages;
use App\Http\Requests\Master\Skill\SkillMstListRequest;
use App\Http\Requests\Master\Skill\SkillMstStoreRequest;
use App\Http\Requests\Master\Skill\SkillMstUpdateRequest;
use App\Http\Resources\Master\SkillResource;
use App\Models\Management\SkillMgmt;
use App\Repositories\Management\SkillMgmtRepository;
use App\Services\CommonService;
use App\Services\SingletonService;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class SkillMstService extends SingletonService
{
  /**
   * SkillMgmt list
   *
   * @param array $payload
   * @return mixed
   */
  public function list(array $payload): mixed
  {
    $validator = (new CommonService())->validationManual(
      (new SkillMstListRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $list = SkillMgmtRepository::list($payload);

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
      (new SkillMstStoreRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    SkillMgmtRepository::store($payload);

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
      (new SkillMstUpdateRequest()),
      $payload
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    SkillMgmtRepository::update($payload);

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
        ['attributes' => SkillMgmt::attributes()['id']]
      );
      throw new InvalidArgumentException($message, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }

    SkillMgmtRepository::delete($id);

    return true;
  }
}
