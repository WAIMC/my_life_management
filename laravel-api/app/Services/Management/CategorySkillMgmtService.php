<?php

namespace App\Services\Management;

use App\Interfaces\Management\CategorySkillMgmtInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Management\CategorySkillMgmtResource;

class CategorySkillMgmtService
{
    public function __construct(
        protected CategorySkillMgmtInterface $categorySkillMgmt
    )
    {
    }

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
        if ($payload['delete']) {
            self::checkExistsCategorySkillMgmt($payload['delete']);
            $this->categorySkillMgmt->executeDelete($payload['delete']);
        }

        // Insert category skill mgmt
        if ($payload['insert']) {
            self::checkNotExistsCategorySkillMgmt($payload['insert']);
            $this->categorySkillMgmt->executeStore($payload['insert']);
        }

        return true;
    }

    /**
     * Check exist category skill mgmt
     *
     * @param array $payload
     * @return void
     */
    private function checkExistsCategorySkillMgmt(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return [(int)$item['category_mgmt_id'],(int)$item['skill_mgmt_id']];
        })->all();

        $CategorySkillMgmtId = $this->categorySkillMgmt->getCategorySkillMgmtId($values);

        // Compare $values and $CategorySkillMgmtId, get the differences
        $differences = array_udiff($values, $CategorySkillMgmtId, function ($a, $b) {
            return strcmp((string)$a, (string)$b);
        });

        // Join the differences into a string
        $diffString = implode(', ', $differences);

        // Throw exception if there are differences
        if (!empty($differences)) {
            throw new LogicException(
                Messages::getMessage(
                    Messages::E0017,
                    [
                        'attributes' => __('messages.category_skill_mgmt_id') . ': ' . $diffString,
                        'tableName' => __('messages.category_skill_mgmt')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }

    /**
     * Check not exist category skill mgmt
     *
     * @param array $payload
     * @return void
     */
    private function checkNotExistsCategorySkillMgmt(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return [(int)$item['category_mgmt_id'],(int)$item['skill_mgmt_id']];
        })->all();

        $CategorySkillMgmtId = $this->categorySkillMgmt->getCategorySkillMgmtId($values)->toArray();
        $diffString = implode(', ', $CategorySkillMgmtId);

        // Throw exception if there are exist
        if (!empty($CategorySkillMgmtId)) {
            throw new LogicException(
                Messages::getMessage(
                    Messages::E0020,
                    [
                        'attributes' => __('messages.category_skill_mgmt_id') . ': ' . $diffString,
                        'tableName' => __('messages.category_skill_mgmt')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }
}
