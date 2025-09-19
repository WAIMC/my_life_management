<?php

namespace App\Services\Master;

use App\Interfaces\Master\DepartmentManagementMstInterface;
use App\Interfaces\Master\DepartmentMstInterface;
use App\Interfaces\Master\PolicyDepartmentMstInterface;
use App\Http\Resources\Master\DepartmentManagementMstResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class DepartmentManagementMstService
{
    protected DepartmentManagementMstInterface $departmentManagementMstRepository;
    protected DepartmentMstInterface $departmentMstRepository;
    protected PolicyDepartmentMstInterface $policyDepartmentMstRepository;

    /**
     * Constructor
     *
     * @param DepartmentManagementMstInterface $departmentManagementMstRepository
     * @param DepartmentMstInterface $departmentMstRepository
     * @param PolicyDepartmentMstInterface $policyDepartmentMstRepository
     */
    public function __construct(
        DepartmentManagementMstInterface $departmentManagementMstRepository,
        DepartmentMstInterface           $departmentMstRepository,
        PolicyDepartmentMstInterface     $policyDepartmentMstRepository
    )
    {
        $this->departmentManagementMstRepository = $departmentManagementMstRepository;
        $this->departmentMstRepository = $departmentMstRepository;
        $this->policyDepartmentMstRepository = $policyDepartmentMstRepository;
    }

    /**
     * Get all department management relations with optional filtering
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $records = $this->departmentManagementMstRepository->getAll($payload);
        return DepartmentManagementMstResource::collection($records);
    }

    /**
     * Get department management relation by IDs
     *
     * @param int $departmentId
     * @param int $policyDepartmentId
     * @return DepartmentManagementMstResource
     */
    public function getById(int $departmentId, int $policyDepartmentId): DepartmentManagementMstResource
    {
        try {
            $record = $this->departmentManagementMstRepository->getById($departmentId, $policyDepartmentId);
            return new DepartmentManagementMstResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Department management relation not found: ' . $departmentId . '-' . $policyDepartmentId);
            throw $e;
        }
    }

    /**
     * Create new department management relation
     *
     * @param array $payload
     * @return DepartmentManagementMstResource
     * @throws Exception
     */
    public function create(array $payload): DepartmentManagementMstResource
    {
        // Validate department_id exists
        try {
            $this->departmentMstRepository->getById($payload['department_id']);
        } catch (ModelNotFoundException $e) {
            Log::error('Referenced department not found: ' . $payload['department_id']);
            throw new Exception('Referenced department does not exist');
        }

        // Validate policy_department_id exists
        try {
            $this->policyDepartmentMstRepository->getById($payload['policy_department_id']);
        } catch (ModelNotFoundException $e) {
            Log::error('Referenced policy department not found: ' . $payload['policy_department_id']);
            throw new Exception('Referenced policy department does not exist');
        }

        // Check if the relation already exists
        try {
            $this->departmentManagementMstRepository->getById($payload['department_id'], $payload['policy_department_id']);
            throw new Exception('Department management relation already exists');
        } catch (ModelNotFoundException $e) {
            // The relation doesn't exist, so we can create it
            $record = $this->departmentManagementMstRepository->create($payload);
            return new DepartmentManagementMstResource($record);
        }
    }

    /**
     * Delete department management relation
     *
     * @param int $departmentId
     * @param int $policyDepartmentId
     * @return bool
     */
    public function delete(int $departmentId, int $policyDepartmentId): bool
    {
        try {
            return $this->departmentManagementMstRepository->delete($departmentId, $policyDepartmentId);
        } catch (ModelNotFoundException $e) {
            Log::error('Department management relation not found for deletion: ' . $departmentId . '-' . $policyDepartmentId);
            throw $e;
        }
    }

    /**
     * Get department management relations by department ID
     *
     * @param int $departmentId
     * @return AnonymousResourceCollection
     */
    public function getByDepartmentId(int $departmentId): AnonymousResourceCollection
    {
        try {
            // First verify the department exists
            $this->departmentMstRepository->getById($departmentId);

            $records = $this->departmentManagementMstRepository->getByDepartmentId($departmentId);
            return DepartmentManagementMstResource::collection($records);
        } catch (ModelNotFoundException $e) {
            Log::error('Department not found: ' . $departmentId);
            throw $e;
        }
    }

    /**
     * Get department management relations by policy department ID
     *
     * @param int $policyDepartmentId
     * @return AnonymousResourceCollection
     */
    public function getByPolicyDepartmentId(int $policyDepartmentId): AnonymousResourceCollection
    {
        try {
            // First verify the policy department exists
            $this->policyDepartmentMstRepository->getById($policyDepartmentId);

            $records = $this->departmentManagementMstRepository->getByPolicyDepartmentId($policyDepartmentId);
            return DepartmentManagementMstResource::collection($records);
        } catch (ModelNotFoundException $e) {
            Log::error('Policy department not found: ' . $policyDepartmentId);
            throw $e;
        }
    }
}
