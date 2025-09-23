<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Master\Department\DeleteDepartmentMstHistRequest;
use App\Http\Requests\History\Master\Department\DepartmentMstHistListRequest;
use App\Http\Requests\History\Master\Department\StoreDepartmentMstHistRequest;
use App\Http\Requests\History\Master\Department\UpdateDepartmentMstHistRequest;
use App\Http\Resources\History\Master\DepartmentMstHistResource;
use App\Services\History\Master\DepartmentMstHistService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DepartmentMstHistController extends Controller
{
    protected DepartmentMstHistService $departmentMstHistService;

    /**
     * Constructor
     *
     * @param DepartmentMstHistService $departmentMstHistService
     */
    public function __construct(DepartmentMstHistService $departmentMstHistService)
    {
        $this->departmentMstHistService = $departmentMstHistService;
    }

    /**
     * Get a listing of department history records
     *
     * @param DepartmentMstHistListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(DepartmentMstHistListRequest $request): AnonymousResourceCollection
    {
        return $this->departmentMstHistService->getAll($request->validated());
    }

    /**
     * Get department history by ID
     *
     * @param string $id
     * @return DepartmentMstHistResource
     */
    public function show(string $id): DepartmentMstHistResource
    {
        return $this->departmentMstHistService->getById((int)$id);
    }

    /**
     * Create a new department history record
     *
     * @param StoreDepartmentMstHistRequest $request
     * @return DepartmentMstHistResource
     */
    public function store(StoreDepartmentMstHistRequest $request): DepartmentMstHistResource
    {
        $data = $request->validated();
        $data['created_at'] = now()->format('Y-m-d H:i:s');

        return $this->departmentMstHistService->create($data);
    }

    /**
     * Update a new department history record
     *
     * @param UpdateDepartmentMstHistRequest $request
     * @param string $id
     * @return DepartmentMstHistResource
     */
    public function update(UpdateDepartmentMstHistRequest $request, string $id): DepartmentMstHistResource
    {
        return $this->departmentMstHistService->update($request->all(), $id);
    }

    /**
     * Update a new department history record
     *
     * @param DeleteDepartmentMstHistRequest $request
     * @param string $id
     * @return bool
     */
    public function delete(DeleteDepartmentMstHistRequest $request, string $id): bool
    {
        return $this->departmentMstHistService->delete((int)$id);
    }

    /**
     * Get department history by ID
     *
     * @param $request
     * @param int $departmentMstId
     * @return AnonymousResourceCollection
     */
    public function getByDepartmentId($request, int $departmentMstId): AnonymousResourceCollection
    {
        return $this->departmentMstHistService->getByDepartmentId($departmentMstId, $request->all());
    }
}
