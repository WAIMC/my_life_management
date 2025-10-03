<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Requests\History\Master\DepartmentMstHist\ListDepartmentMstHistRequest;
use App\Http\Requests\History\Master\DepartmentMstHist\StoreDepartmentMstHistRequest;
use App\Http\Requests\History\Master\DepartmentMstHist\UpdateDepartmentMstHistRequest;
use App\Http\Requests\History\Master\DepartmentMstHist\DeleteDepartmentMstHistRequest;
use App\Services\History\Master\DepartmentMstHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentMstHistController extends Controller
{
    public function __construct(
        protected DepartmentMstHistService $departmentMstHist
    )
    {
    }
    
    /**
     * DepartmentMstHist list
     *
     * @param ListDepartmentMstHistRequest $request
     * @return JsonResource
     */
    public function list(ListDepartmentMstHistRequest $request): JsonResource
    {
        return $this->departmentMstHist->list($request->all());
    }

    /**
     * Store department mst hist
     *
     * @param StoreDepartmentMstHistRequest $request
     * @return int
     */
    public function store(StoreDepartmentMstHistRequest $request): int
    {
        return $this->departmentMstHist->store($request->all());
    }

    /**
     * Update department mst hist
     *
     * @param UpdateDepartmentMstHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateDepartmentMstHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->departmentMstHist->update($payload);
    }

    /**
     * Delete department mst hist
     *
     * @param DeleteDepartmentMstHistRequest $request
     * @return void
     */
    public function delete(DeleteDepartmentMstHistRequest $request): void
    {
        $this->departmentMstHist->delete($request->all());
    }
}
