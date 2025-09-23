<?php

namespace App\Services\History\Master;

use App\Http\Resources\History\Master\DepartmentMstHistResource;
use App\Interfaces\History\Master\DepartmentMstHistInterface;
use App\Interfaces\Master\DepartmentMstInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentMstHistService
{
    /**
     * @var DepartmentMstHistInterface
     */
    protected DepartmentMstHistInterface $departmentMstHistRepository;

    /**
     * @var DepartmentMstInterface
     */
    protected DepartmentMstInterface $departmentMstRepository;

    /**
     * DepartmentMstHistService constructor.
     *
     * @param DepartmentMstHistInterface $departmentMstHistRepository
     * @param DepartmentMstInterface $departmentMstRepository
     */
    public function __construct(
        DepartmentMstHistInterface $departmentMstHistRepository,
        DepartmentMstInterface $departmentMstRepository
    ) {
        $this->departmentMstHistRepository = $departmentMstHistRepository;
        $this->departmentMstRepository = $departmentMstRepository;
    }

    /**
     * Get all department history records
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $result = $this->departmentMstHistRepository->getAll($payload);
        return DepartmentMstHistResource::collection($result);
    }

    /**
     * Get department history record by ID
     *
     * @param int $id
     * @return DepartmentMstHistResource
     */
    public function getById(int $id): DepartmentMstHistResource
    {
        $departmentMstHist = $this->departmentMstHistRepository->getById($id);

        if (!$departmentMstHist) {
            throw new NotFoundHttpException('Department history record not found');
        }

        return new DepartmentMstHistResource($departmentMstHist);
    }

    /**
     * Get history records by department ID
     *
     * @param int $departmentMstId
     * @param array $payload
     * @return AnonymousResourceCollection
     * @throws NotFoundHttpException
     */
    public function getByDepartmentId(int $departmentMstId, array $payload): AnonymousResourceCollection
    {
        $department = $this->departmentMstRepository->getById($departmentMstId);

        if (!$department) {
            throw new NotFoundHttpException('Department not found');
        }

        $result = $this->departmentMstHistRepository->getByDepartmentId($departmentMstId, $payload);
        return DepartmentMstHistResource::collection($result);
    }

    /**
     * Create new department history record
     *
     * @param array $payload
     * @return DepartmentMstHistResource
     * @throws NotFoundHttpException
     */
    public function create(array $payload): DepartmentMstHistResource
    {
        if (isset($payload['department_mst_id'])) {
            $department = $this->departmentMstRepository->getById($payload['department_mst_id']);

            if (!$department) {
                throw new NotFoundHttpException('Department not found');
            }
        }

        // Set author_id to current authenticated user if not provided
        if (!isset($payload['author_id']) && Auth::check()) {
            $payload['author_id'] = Auth::id();
        }

        $departmentMstHist = $this->departmentMstHistRepository->create($payload);
        return new DepartmentMstHistResource($departmentMstHist);
    }

    /**
     * Update department history record
     *
     * @param array $payload
     * @param int $id
     * @return DepartmentMstHistResource
     * @throws NotFoundHttpException
     */
    public function update(array $payload, int $id): DepartmentMstHistResource
    {
        $departmentMstHist = $this->departmentMstHistRepository->getById($id);

        if (!$departmentMstHist) {
            throw new NotFoundHttpException('Department history record not found');
        }

        if (isset($payload['department_mst_id'])) {
            $department = $this->departmentMstRepository->getById($payload['department_mst_id']);

            if (!$department) {
                throw new NotFoundHttpException('Department not found');
            }
        }

        $updatedDepartmentMstHist = $this->departmentMstHistRepository->update($payload, $id);
        return new DepartmentMstHistResource($updatedDepartmentMstHist);
    }

    /**
     * Delete department history record
     *
     * @param int $id
     * @return bool
     * @throws NotFoundHttpException
     */
    public function delete(int $id): bool
    {
        $departmentMstHist = $this->departmentMstHistRepository->getById($id);

        if (!$departmentMstHist) {
            throw new NotFoundHttpException('Department history record not found');
        }

        return $this->departmentMstHistRepository->delete($id);
    }
}
