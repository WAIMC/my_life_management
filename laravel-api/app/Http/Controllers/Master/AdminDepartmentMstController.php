<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\AdminDepartment\AdminDepartmentMstListRequest;
use App\Http\Requests\Master\AdminDepartment\AdminDepartmentMstUpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Master\AdminDepartmentMstService;

class AdminDepartmentMstController extends Controller
{
    public function __construct(
        private AdminDepartmentMstService $adminDepartmentService
    ) {}

    /**
     * AdminMst department list
     *
     * @param AdminDepartmentMstListRequest $request
     * @return JsonResource
     */
    public function list(AdminDepartmentMstListRequest $request): JsonResource
    {
        return $this->adminDepartmentService->list($request->all());
    }

    /**
     * Update admin department
     *
     * @param AdminDepartmentMstUpdateRequest $request
     * @return bool
     */
    public function update(AdminDepartmentMstUpdateRequest $request): bool
    {
        return $this->adminDepartmentService->update($request->all());
    }
}
