<?php

namespace App\Services\Master;

use App\Interfaces\Master\AdminRoleMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Master\AdminRoleResource;

class AdminRoleMstService
{
    public function __construct(
        private AdminRoleMstInterface $adminRole
    )
    {
    }

    /**
     * Get admin role list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->adminRole->list($payload);

        return AdminRoleResource::collection($list);
    }

    /**
     * Update admin role
     *
     * @param array $payload
     * @return bool
     */
    public function update(array $payload): bool
    {
        // Delete api role
        if ($payload['delete']) {
            self::checkExistsAdminRole($payload['delete']);
            $this->adminRole->executeDelete($payload['delete']);
        }

        // Insert api role
        if ($payload['insert']) {
            self::checkNotExistsAdminRole($payload['insert']);
            $this->adminRole->executeStore($payload['insert']);
        }

        return true;
    }

    /**
     * Check exist admin role
     *
     * @param array $payload
     * @return void
     */
    private function checkExistsAdminRole(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return '(' . (int)$item['admin_id'] . ', ' . (int)$item['role_id'] . ')';
        })->all();

        $adminRoleId = $this->adminRole->getAdminRoleId($values);

        // Compare $values and $adminRoleId, get the differences
        $differences = array_udiff($values, $adminRoleId, function ($a, $b) {
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
     * Check not exist admin role
     *
     * @param array $payload
     * @return void
     */
    private function checkNotExistsAdminRole(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return '(' . (int)$item['admin_id'] . ', ' . (int)$item['role_id'] . ')';
        })->all();

        $adminRoleId =  $this->adminRole->getAdminRoleId($values)->toArray();
        $diffString = implode(', ', $adminRoleId);

        // Throw exception if there are exist
        if (!empty($adminRoleId)) {
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
