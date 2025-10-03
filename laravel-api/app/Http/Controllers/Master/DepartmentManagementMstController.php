<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\DepartmentManagementMst\ListDepartmentManagementMstRequest;
use App\Http\Requests\Master\DepartmentManagementMst\UpdateDepartmentManagementMstRequest;
use App\Services\Master\DepartmentManagementMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentManagementMstController extends Controller
{
    public function __construct(
        protected DepartmentManagementMstService $departmentManagementMst
    )
    {
    }
    
    /**
     * DepartmentManagementMst list
     *
     * @param ListDepartmentManagementMstRequest $request
     * @return JsonResource
     */
    public function list(ListDepartmentManagementMstRequest $request): JsonResource
    {
        return $this->departmentManagementMst->list($request->all());
    }

    /**
     * Update department management mst
     *
     * @param UpdateDepartmentManagementMstRequest $request
     * @return bool
     */
    public function update(UpdateDepartmentManagementMstRequest $request): bool
    {
        return $this->departmentManagementMst->update($request->all());
    }
}
