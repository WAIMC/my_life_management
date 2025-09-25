<?php

namespace App\Services\Master;

use App\Constants\CommonVal;
use App\Constants\Messages;
use App\Interfaces\Master\DepartmentManagementMstInterface;
use App\Interfaces\Master\DepartmentMstInterface;
use App\Interfaces\Master\PolicyDepartmentMstInterface;
use App\Http\Resources\Master\DepartmentManagementMstResource;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

class DepartmentManagementMstService
{
    /**
     * Constructor
     *
     * @param DepartmentManagementMstInterface $departmentManagementMst
     * @param DepartmentMstInterface $departmentMst
     * @param PolicyDepartmentMstInterface $policyDepartmentMst
     */
    public function __construct(
        protected DepartmentManagementMstInterface $departmentManagementMst,
        protected DepartmentMstInterface           $departmentMst,
        protected PolicyDepartmentMstInterface     $policyDepartmentMst
    )
    {}

    /**
     * Get all department management relations with optional filtering
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
     * Create new department management relation
     *
     * @param array $payload
     * @return bool
     */
    public function update(array $payload): bool
    {
        // Delete department management
        if ($payload['delete']) {
            self::checkExistsDepartmentManagement($payload['delete']);
            $this->departmentManagementMst->executeDelete($payload['delete']);
        }

        // Insert department management
        if ($payload['insert']) {
            self::checkNotExistsDepartmentManagement($payload['insert']);
            $this->departmentManagementMst->executeStore($payload['insert']);
        }

        return true;
    }

    /**
     * Check exist department management
     *
     * @param array $payload
     * @return void
     */
    private function checkExistsDepartmentManagement(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            return '(' . (int)$item['department_id'] . ', ' . (int)$item['policy_department_id'] . ')';
        })->all();

        $adminDepartmentId = $this->departmentManagementMst->getDepartmentMgmtMstId($values);

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
                        'attributes' => __('messages.department_management_id') . ': ' . $diffString,
                        'tableName' => __('messages.department_management_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }

    /**
     * Check not exist department management
     *
     * @param array $payload
     * @return void
     */
    private function checkNotExistsDepartmentManagement(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            return '(' . (int)$item['department_id'] . ', ' . (int)$item['policy_department_id'] . ')';
        })->all();

        $adminDepartmentId = $this->departmentManagementMst->getDepartmentMgmtMstId($values)->toArray();
        $diffString = implode(', ', $adminDepartmentId);

        // Throw exception if there are exist
        if (!empty($adminDepartmentId)) {
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
