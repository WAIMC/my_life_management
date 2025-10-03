<?php

namespace App\Services\Master;

use App\Interfaces\Master\DepartmentManagementMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Master\DepartmentManagementMstResource;

class DepartmentManagementMstService
{
    public function __construct(
        protected DepartmentManagementMstInterface $departmentManagementMst
    )
    {
    }

    /**
     * Get department management mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->departmentManagementMst->list($payload);

        return DepartmentManagementMstResource::collection($list);
    }

    /**
     * Update department management mst
     *
     * @param array $payload
     * @return bool
     */
    public function update(array $payload): bool
    {
        // Delete department management mst
        if ($payload['delete']) {
            self::checkExistsDepartmentManagementMst($payload['delete']);
            $this->departmentManagementMst->executeDelete($payload['delete']);
        }

        // Insert department management mst
        if ($payload['insert']) {
            self::checkNotExistsDepartmentManagementMst($payload['insert']);
            $this->departmentManagementMst->executeStore($payload['insert']);
        }

        return true;
    }

    /**
     * Check exist department management mst
     *
     * @param array $payload
     * @return void
     */
    private function checkExistsDepartmentManagementMst(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return [(int)$item['department_mst_id'],(int)$item['policy_department_mst_id']];
        })->all();

        $DepartmentManagementMstId = $this->departmentManagementMst->getDepartmentManagementMstId($values);

        // Compare $values and $DepartmentManagementMstId, get the differences
        $differences = array_udiff($values, $DepartmentManagementMstId, function ($a, $b) {
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
                        'attributes' => __('messages.department_management_id') . ': ' . $diffString,
                        'tableName' => __('messages.department_management_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }

    /**
     * Check not exist department management mst
     *
     * @param array $payload
     * @return void
     */
    private function checkNotExistsDepartmentManagementMst(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return [(int)$item['department_mst_id'],(int)$item['policy_department_mst_id']];
        })->all();

        $DepartmentManagementMstId = $this->departmentManagementMst->getDepartmentManagementMstId($values)->toArray();
        $diffString = implode(', ', $DepartmentManagementMstId);

        // Throw exception if there are exist
        if (!empty($DepartmentManagementMstId)) {
            throw new LogicException(
                Messages::getMessage(
                    Messages::E0020,
                    [
                        'attributes' => __('messages.department_management_id') . ': ' . $diffString,
                        'tableName' => __('messages.department_management_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }
}
