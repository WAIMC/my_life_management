<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\AdminDepartmentMst\ListAdminDepartmentMstRequest;
use App\Http\Requests\Master\AdminDepartmentMst\UpdateAdminDepartmentMstRequest;
use App\Services\Master\AdminDepartmentMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminDepartmentMstController extends Controller
{
    public function __construct(
        protected AdminDepartmentMstService $adminDepartmentMst
    )
    {
    }
    
    /**
     * AdminDepartmentMst list
     *
     * @param ListAdminDepartmentMstRequest $request
     * @return JsonResource
     */
    public function list(ListAdminDepartmentMstRequest $request): JsonResource
    {
        return $this->adminDepartmentMst->list($request->all());
    }

    /**
     * Update admin department mst
     *
     * @param UpdateAdminDepartmentMstRequest $request
     * @return bool
     */
    public function update(UpdateAdminDepartmentMstRequest $request): bool
    {
        return $this->adminDepartmentMst->update($request->all());
    }
}
