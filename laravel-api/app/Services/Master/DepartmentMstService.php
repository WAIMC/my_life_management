<?php

namespace App\Services\Master;

use App\Interfaces\Master\DepartmentMstInterface;
use App\Http\Resources\Master\DepartmentMstResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class DepartmentMstService
{
    protected DepartmentMstInterface $departmentMstRepository;

    /**
     * Constructor
     *
     * @param DepartmentMstInterface $departmentMstRepository
     */
    public function __construct(DepartmentMstInterface $departmentMstRepository)
    {
        $this->departmentMstRepository = $departmentMstRepository;
    }

    /**
     * Get all departments with optional filtering
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $records = $this->departmentMstRepository->getAll($payload);
        return DepartmentMstResource::collection($records);
    }

    /**
     * Get department by ID
     *
     * @param int $id
     * @return DepartmentMstResource
     */
    public function getById(int $id): DepartmentMstResource
    {
        try {
            $record = $this->departmentMstRepository->getById($id);
            return new DepartmentMstResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Department not found: ' . $id);
            throw $e;
        }
    }

    /**
     * Get department by code
     *
     * @param string $code
     * @return DepartmentMstResource
     */
    public function getByCode(string $code): DepartmentMstResource
    {
        try {
            $record = $this->departmentMstRepository->getByCode($code);
            return new DepartmentMstResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Department not found with code: ' . $code);
            throw $e;
        }
    }

    /**
     * Create new department
     *
     * @param array $payload
     * @return DepartmentMstResource
     * @throws Exception
     */
    public function create(array $payload): DepartmentMstResource
    {
        // Check if code already exists
        try {
            $existingDepartment = $this->departmentMstRepository->getByCode($payload['code']);
            if ($existingDepartment) {
                throw new Exception('Department with code ' . $payload['code'] . ' already exists');
            }
        } catch (ModelNotFoundException $e) {
            // Code doesn't exist, which is what we want
        }

        $record = $this->departmentMstRepository->create($payload);
        return new DepartmentMstResource($record);
    }

    /**
     * Update department
     *
     * @param array $payload
     * @param int $id
     * @return DepartmentMstResource
     * @throws Exception
     */
    public function update(array $payload, int $id): DepartmentMstResource
    {
        try {
            // Check if code is being updated and already exists for another department
            if (isset($payload['code'])) {
                try {
                    $existingDepartment = $this->departmentMstRepository->getByCode($payload['code']);
                    if ($existingDepartment && $existingDepartment->id != $id) {
                        throw new Exception('Department with code ' . $payload['code'] . ' already exists');
                    }
                } catch (ModelNotFoundException $e) {
                    // Code doesn't exist, which is what we want
                }
            }

            $record = $this->departmentMstRepository->update($payload, $id);
            return new DepartmentMstResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Department not found for update: ' . $id);
            throw $e;
        }
    }

    /**
     * Delete department
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        try {
            // Check if the department is used by any admin or management relation
            $department = $this->departmentMstRepository->getById($id);

            if ($department->adminDepartments()->count() > 0) {
                throw new Exception('Cannot delete department that is assigned to admins');
            }

            if ($department->departmentManagements()->count() > 0) {
                throw new Exception('Cannot delete department that has management relations');
            }

            return $this->departmentMstRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            Log::error('Department not found for deletion: ' . $id);
            throw $e;
        }
    }
}
