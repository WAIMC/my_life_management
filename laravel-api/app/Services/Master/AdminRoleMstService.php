<?php

namespace App\Services\Master;

use App\Interfaces\Master\AdminRoleMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Master\AdminRoleMstResource;

class AdminRoleMstService
{
    public function __construct(
        protected AdminRoleMstInterface $adminRoleMst
    )
    {
    }

    /**
     * Get admin role mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->adminRoleMst->list($payload);

        return AdminRoleMstResource::collection($list);
    }

    /**
     * Update admin role mst
     *
     * @param array $payload
     * @return bool
     */
    public function update(array $payload): bool
    {
        // Don't allow editing of personal role without role admin
        if ($this->adminRoleMst->isMyRole($payload)) {
            throw new LogicException(Messages::E0018, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
        }

        // Delete admin role mst
        if ($payload['delete']) {
            self::checkExistsAdminRoleMst($payload['delete']);
            $this->adminRoleMst->executeDelete($payload['delete']);
        }

        // Insert admin role mst
        if ($payload['insert']) {
            self::checkNotExistsAdminRoleMst($payload['insert']);
            $this->adminRoleMst->executeStore($payload['insert']);
        }

        return true;
    }

    /**
     * Check exist admin role mst
     *
     * @param array $payload
     * @return void
     */
    private function checkExistsAdminRoleMst(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return [(int)$item['admin_mst_id'],(int)$item['role_mst_id']];
        })->all();

        $AdminRoleMstId = $this->adminRoleMst->getAdminRoleMstId($values);

        // Compare $values and $AdminRoleMstId, get the differences
        $differences = array_udiff($values, $AdminRoleMstId, function ($a, $b) {
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
                        'attributes' => __('messages.admin_role_id') . ': ' . $diffString,
                        'tableName' => __('messages.admin_role_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }

    /**
     * Check not exist admin role mst
     *
     * @param array $payload
     * @return void
     */
    private function checkNotExistsAdminRoleMst(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return [(int)$item['admin_mst_id'],(int)$item['role_mst_id']];
        })->all();

        $AdminRoleMstId = $this->adminRoleMst->getAdminRoleMstId($values)->toArray();
        $diffString = implode(', ', $AdminRoleMstId);

        // Throw exception if there are exist
        if (!empty($AdminRoleMstId)) {
            throw new LogicException(
                Messages::getMessage(
                    Messages::E0020,
                    [
                        'attributes' => __('messages.admin_role_id') . ': ' . $diffString,
                        'tableName' => __('messages.admin_role_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }
}
