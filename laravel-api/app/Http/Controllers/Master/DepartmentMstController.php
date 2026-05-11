<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\DepartmentMst\ListDepartmentMstRequest;
use App\Http\Requests\Master\DepartmentMst\StoreDepartmentMstRequest;
use App\Http\Requests\Master\DepartmentMst\UpdateDepartmentMstRequest;
use App\Http\Requests\Master\DepartmentMst\DeleteDepartmentMstRequest;
use App\Services\Master\DepartmentMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentMstController extends Controller
{
    public function __construct(
        protected DepartmentMstService $departmentMst
    )
    {
    }
    
    /**
     * DepartmentMst list
     *
     * @param ListDepartmentMstRequest $request
     * @return JsonResource
     */
    public function list(ListDepartmentMstRequest $request): JsonResource
    {
        return $this->departmentMst->list($request->all());
    }

    /**
     * Store department mst
     *
     * @param StoreDepartmentMstRequest $request
     * @return int
     */
    public function store(StoreDepartmentMstRequest $request): int
    {
        return $this->departmentMst->store($request->all());
    }

    /**
     * Update department mst
     *
     * @param UpdateDepartmentMstRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateDepartmentMstRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->departmentMst->update($payload);
    }

    /**
     * Delete department mst
     *
     * @param DeleteDepartmentMstRequest $request
     * @return void
     */
    public function delete(DeleteDepartmentMstRequest $request): void
    {
        $this->departmentMst->delete($request->all());
    }
}
