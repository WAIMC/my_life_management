<?php

namespace App\Services\Master;

use App\Interfaces\Master\AdminDepartmentMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Master\AdminDepartmentMstResource;

class AdminDepartmentMstService
{
    public function __construct(
        protected AdminDepartmentMstInterface $adminDepartmentMst
    )
    {
    }

    /**
     * Get admin department mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->adminDepartmentMst->list($payload);

        return AdminDepartmentMstResource::collection($list);
    }

    /**
     * Update admin department mst
     *
     * @param array $payload
     * @return bool
     */
    public function update(array $payload): bool
    {
        // Delete admin department mst
        if ($payload['delete']) {
            self::checkExistsAdminDepartmentMst($payload['delete']);
            $this->adminDepartmentMst->executeDelete($payload['delete']);
        }

        // Insert admin department mst
        if ($payload['insert']) {
            self::checkNotExistsAdminDepartmentMst($payload['insert']);
            $this->adminDepartmentMst->executeStore($payload['insert']);
        }

        return true;
    }

    /**
     * Check exist admin department mst
     *
     * @param array $payload
     * @return void
     */
    private function checkExistsAdminDepartmentMst(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return [(int)$item['admin_mst_id'],(int)$item['department_mst_id']];
        })->all();

        $AdminDepartmentMstId = $this->adminDepartmentMst->getAdminDepartmentMstId($values);

        // Compare $values and $AdminDepartmentMstId, get the differences
        $differences = array_udiff($values, $AdminDepartmentMstId, function ($a, $b) {
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
     * Check not exist admin department mst
     *
     * @param array $payload
     * @return void
     */
    private function checkNotExistsAdminDepartmentMst(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return [(int)$item['admin_mst_id'],(int)$item['department_mst_id']];
        })->all();

        $AdminDepartmentMstId = $this->adminDepartmentMst->getAdminDepartmentMstId($values)->toArray();
        $diffString = implode(', ', $AdminDepartmentMstId);

        // Throw exception if there are exist
        if (!empty($AdminDepartmentMstId)) {
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
