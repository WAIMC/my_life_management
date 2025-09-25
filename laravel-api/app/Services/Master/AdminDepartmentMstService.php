<?php

namespace App\Services\Master;

use App\Constants\CommonVal;
use App\Constants\Messages;
use App\Interfaces\Master\AdminDepartmentMstInterface;
use App\Http\Resources\Master\AdminDepartmentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

class AdminDepartmentMstService
{
    public function __construct(
        protected AdminDepartmentMstInterface $adminDepartment
    )
    {
    }

    /**
     * Get admin department list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->adminDepartment->list($payload);

        return AdminDepartmentResource::collection($list);
    }

    /**
     * Update admin department
     *
     * @param array $payload
     * @return bool
     */
    public function update(array $payload): bool
    {
        // Delete admin department
        if ($payload['delete']) {
            self::checkExistsAdminDepartment($payload['delete']);
            $this->adminDepartment->executeDelete($payload['delete']);
        }

        // Insert admin department
        if ($payload['insert']) {
            self::checkNotExistsAdminDepartment($payload['insert']);
            $this->adminDepartment->executeStore($payload['insert']);
        }

        return true;
    }

    /**
     * Check exist admin department
     *
     * @param array $payload
     * @return void
     */
    private function checkExistsAdminDepartment(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return '(' . (int)$item['admin_id'] . ', ' . (int)$item['department_id'] . ')';
        })->all();

        $adminDepartmentId = $this->adminDepartment->getAdminDepartmentId($values);

        // Compare $values and $adminDepartmentId, get the differences
        $differences = array_udiff($values, $adminDepartmentId, function ($a, $b) {
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
                        'attributes' => __('messages.admin_department_id') . ': ' . $diffString,
                        'tableName' => __('messages.admin_department_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }

    /**
     * Check not exist admin department
     *
     * @param array $payload
     * @return void
     */
    private function checkNotExistsAdminDepartment(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return '(' . (int)$item['admin_id'] . ', ' . (int)$item['department_id'] . ')';
        })->all();

        $adminDepartmentId = $this->adminDepartment->getAdminDepartmentId($values)->toArray();
        $diffString = implode(', ', $adminDepartmentId);

        // Throw exception if there are exist
        if (!empty($adminDepartmentId)) {
            throw new LogicException(
                Messages::getMessage(
                    Messages::E0020,
                    [
                        'attributes' => __('messages.admin_department_id') . ': ' . $diffString,
                        'tableName' => __('messages.admin_department_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }
}
