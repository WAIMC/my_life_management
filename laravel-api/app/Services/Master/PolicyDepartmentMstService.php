<?php

namespace App\Services\Master;

use App\Interfaces\Master\PolicyDepartmentMstInterface;
use App\Http\Resources\Master\PolicyDepartmentMstResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class PolicyDepartmentMstService
{
    protected PolicyDepartmentMstInterface $policyDepartmentMstRepository;

    /**
     * Constructor
     *
     * @param PolicyDepartmentMstInterface $policyDepartmentMstRepository
     */
    public function __construct(PolicyDepartmentMstInterface $policyDepartmentMstRepository)
    {
        $this->policyDepartmentMstRepository = $policyDepartmentMstRepository;
    }

    /**
     * Get all policy departments with optional filtering
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $records = $this->policyDepartmentMstRepository->getAll($payload);
        return PolicyDepartmentMstResource::collection($records);
    }

    /**
     * Get policy department by ID
     *
     * @param int $id
     * @return PolicyDepartmentMstResource
     */
    public function getById(int $id): PolicyDepartmentMstResource
    {
        try {
            $record = $this->policyDepartmentMstRepository->getById($id);
            return new PolicyDepartmentMstResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Policy department not found: ' . $id);
            throw $e;
        }
    }

    /**
     * Get policy departments by table name
     *
     * @param string $tableName
     * @return AnonymousResourceCollection
     */
    public function getByTableName(string $tableName): AnonymousResourceCollection
    {
        $records = $this->policyDepartmentMstRepository->getByTableName($tableName);
        return PolicyDepartmentMstResource::collection($records);
    }

    /**
     * Get policy department by table name and row ID
     *
     * @param string $tableName
     * @param int $rowId
     * @return PolicyDepartmentMstResource
     */
    public function getByTableNameAndRowId(string $tableName, int $rowId): PolicyDepartmentMstResource
    {
        try {
            $record = $this->policyDepartmentMstRepository->getByTableNameAndRowId($tableName, $rowId);
            return new PolicyDepartmentMstResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Policy department not found for table ' . $tableName . ' and row ' . $rowId);
            throw $e;
        }
    }

    /**
     * Create new policy department
     *
     * @param array $payload
     * @return PolicyDepartmentMstResource
     * @throws Exception
     */
    public function create(array $payload): PolicyDepartmentMstResource
    {
        // Check if a policy with the same table_name and row_id already exists
        try {
            $this->policyDepartmentMstRepository->getByTableNameAndRowId($payload['table_name'], $payload['row_id']);
            throw new Exception('Policy department for table ' . $payload['table_name'] . ' and row ' . $payload['row_id'] . ' already exists');
        } catch (ModelNotFoundException $e) {
            // This is what we want - record doesn't exist yet
            $record = $this->policyDepartmentMstRepository->create($payload);
            return new PolicyDepartmentMstResource($record);
        }
    }

    /**
     * Update policy department
     *
     * @param array $payload
     * @param int $id
     * @return PolicyDepartmentMstResource
     * @throws Exception
     */
    public function update(array $payload, int $id): PolicyDepartmentMstResource
    {
        try {
            // Check if updating table_name and row_id would create a duplicate
            if (isset($payload['table_name']) && isset($payload['row_id'])) {
                try {
                    $existingRecord = $this->policyDepartmentMstRepository->getByTableNameAndRowId(
                        $payload['table_name'],
                        $payload['row_id']
                    );

                    if ($existingRecord->id != $id) {
                        throw new Exception('Another policy department for table ' . $payload['table_name'] . ' and row ' . $payload['row_id'] . ' already exists');
                    }
                } catch (ModelNotFoundException $e) {
                    // This is fine - no duplicate exists
                }
            }

            $record = $this->policyDepartmentMstRepository->update($payload, $id);
            return new PolicyDepartmentMstResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Policy department not found for update: ' . $id);
            throw $e;
        }
    }

    /**
     * Delete policy department
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        try {
            // Check if the policy department is used in any department management relation
            $policyDepartment = $this->policyDepartmentMstRepository->getById($id);

            if ($policyDepartment->departmentManagements()->count() > 0) {
                throw new Exception('Cannot delete policy department that is used in department management relations');
            }

            return $this->policyDepartmentMstRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            Log::error('Policy department not found for deletion: ' . $id);
            throw $e;
        }
    }
}
