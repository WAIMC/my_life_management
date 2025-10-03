<?php

namespace App\Services\Master;

use App\Interfaces\Master\ApiRoleMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Master\ApiRoleMstResource;

class ApiRoleMstService
{
    public function __construct(
        protected ApiRoleMstInterface $apiRoleMst
    )
    {
    }

    /**
     * Get api role mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->apiRoleMst->list($payload);

        return ApiRoleMstResource::collection($list);
    }

    /**
     * Update api role mst
     *
     * @param array $payload
     * @return bool
     */
    public function update(array $payload): bool
    {
        // Don't allow editing of personal role without role admin
        if ($this->apiRoleMst->isMyRole($payload)) {
            throw new LogicException(Messages::E0018, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
        }

        // Delete api role mst
        if ($payload['delete']) {
            self::checkExistsApiRoleMst($payload['delete']);
            $this->apiRoleMst->executeDelete($payload['delete']);
        }

        // Insert api role mst
        if ($payload['insert']) {
            self::checkNotExistsApiRoleMst($payload['insert']);
            $this->apiRoleMst->executeStore($payload['insert']);
        }

        return true;
    }

    /**
     * Check exist api role mst
     *
     * @param array $payload
     * @return void
     */
    private function checkExistsApiRoleMst(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return [(int)$item['api_mst_id'],(int)$item['role_mst_id']];
        })->all();

        $ApiRoleMstId = $this->apiRoleMst->getApiRoleMstId($values);

        // Compare $values and $ApiRoleMstId, get the differences
        $differences = array_udiff($values, $ApiRoleMstId, function ($a, $b) {
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
     * Check not exist api role mst
     *
     * @param array $payload
     * @return void
     */
    private function checkNotExistsApiRoleMst(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return [(int)$item['api_mst_id'],(int)$item['role_mst_id']];
        })->all();

        $ApiRoleMstId = $this->apiRoleMst->getApiRoleMstId($values)->toArray();
        $diffString = implode(', ', $ApiRoleMstId);

        // Throw exception if there are exist
        if (!empty($ApiRoleMstId)) {
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
