<?php

namespace App\Services\Management;

use App\Services\BaseJunctionService;
use App\Interfaces\Management\CategorySkillMgmtInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Management\CategorySkillMgmtResource;

class CategorySkillMgmtService extends BaseJunctionService
{
  public function __construct(
    protected CategorySkillMgmtInterface $categorySkillMgmt
  ) {}

  /**
   * Get category skill mgmt list
   *
   * @param array $payload
   * @return JsonResource
   */
  public function list(array $payload): JsonResource
  {
    $list = $this->categorySkillMgmt->list($payload);

    return CategorySkillMgmtResource::collection($list);
  }

  /**
   * Update category skill mgmt
   *
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    // Delete category skill mgmt
    if (!empty($payload['delete'])) {
      $this->validateExistence(
        $payload['delete'],
        fn($values) => $this->categorySkillMgmt->getCategorySkillMgmtId($values),
        'category_skill_id',
        'category_skill_mgmt'
      );
      $this->categorySkillMgmt->executeDelete($payload['delete']);
    }

    // Insert category skill mgmt
    if (!empty($payload['insert'])) {
      $this->validateNonExistence(
        $payload['insert'],
        fn($values) => $this->categorySkillMgmt->getCategorySkillMgmtId($values),
        'category_skill_id',
        'category_skill_mgmt'
      );
      $this->categorySkillMgmt->executeStore($payload['insert']);
    }

    return true;
  }
}
