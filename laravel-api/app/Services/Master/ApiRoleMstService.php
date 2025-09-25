<?php

namespace App\Services\Master;

use App\Interfaces\Master\ApiRoleMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Master\ApiRoleResource;

class ApiRoleMstService
{
    public function __construct(
        protected ApiRoleMstInterface $apiRole,
    )
    {
    }

    /**
     * Get api role list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->apiRole->list($payload);

        return ApiRoleResource::collection($list);
    }

    /**
     * Update api role
     *
     * @param array $payload
     * @return bool
     */
    public function update(array $payload): bool
    {
        // Don't allow editing of personal role without role admin
        if ($this->apiRole->isMyRole($payload)) {
            throw new LogicException(Messages::E0018, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
        }

        // Delete api role
        if ($payload['delete']) {
            self::checkExistsApiRole($payload['delete']);
            $this->apiRole->executeDelete($payload['delete']);
        }

        // Insert api role
        if ($payload['insert']) {
            self::checkNotExistsApiRole($payload['insert']);
            $this->apiRole->executeStore($payload['insert']);
        }

        return true;
    }

    /**
     * Check exist api role
     *
     * @param array $payload
     * @return void
     */
    private function checkExistsApiRole(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return '(' . (int)$item['api_id'] . ', ' . (int)$item['role_id'] . ')';
        })->all();

        $apiRoleId = $this->apiRole->getApiRoleId($values);

        // Compare $values and $apiRoleId, get the differences
        $differences = array_udiff($values, $apiRoleId, function ($a, $b) {
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
                        'attributes' => __('messages.api_role_id') . ': ' . $diffString,
                        'tableName' => __('messages.api_role_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }

    /**
     * Check not exist api role
     *
     * @param array $payload
     * @return void
     */
    private function checkNotExistsApiRole(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return '(' . (int)$item['api_id'] . ', ' . (int)$item['role_id'] . ')';
        })->all();

        $apiRoleId = $this->apiRole->getApiRoleId($values)->toArray();
        $diffString = implode(', ', $apiRoleId);

        // Throw exception if there are exist
        if (!empty($apiRoleId)) {
            throw new LogicException(
                Messages::getMessage(
                    Messages::E0020,
                    [
                        'attributes' => __('messages.api_role_id') . ': ' . $diffString,
                        'tableName' => __('messages.api_role_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }
}
