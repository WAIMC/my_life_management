<?php

namespace App\Services\Management;

use App\Services\BaseJunctionService;
use App\Interfaces\Management\CategoryEntryMgmtInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Management\CategoryEntryMgmtResource;

class CategoryEntryMgmtService extends BaseJunctionService
{
  public function __construct(
    protected CategoryEntryMgmtInterface $categoryEntryMgmt
  ) {}

  /**
   * Get category entry mgmt list
   *
   * @param array $payload
   * @return JsonResource
   */
  public function list(array $payload): JsonResource
  {
    $list = $this->categoryEntryMgmt->list($payload);

    return CategoryEntryMgmtResource::collection($list);
  }

  /**
   * Update category entry mgmt
   *
   * @param array $payload
   * @return bool
   */
  public function update(array $payload): bool
  {
    // Delete category entry mgmt
    if (!empty($payload['delete'])) {
      $this->validateExistence(
        $payload['delete'],
        fn($values) => $this->categoryEntryMgmt->getCategoryEntryMgmtId($values),
        'category_entry_id',
        'category_entry_mgmt'
      );
      $this->categoryEntryMgmt->executeDelete($payload['delete']);
    }

    // Insert category entry mgmt
    if (!empty($payload['insert'])) {
      $this->validateNonExistence(
        $payload['insert'],
        fn($values) => $this->categoryEntryMgmt->getCategoryEntryMgmtId($values),
        'category_entry_id',
        'category_entry_mgmt'
      );
      $this->categoryEntryMgmt->executeStore($payload['insert']);
    }

    return true;
  }
}
