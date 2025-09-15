<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\AdminDepartment\AdminDepartmentListRequest;
use App\Http\Requests\Master\AdminDepartment\AdminDepartmentUpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Master\AdminDepartmentService;

class AdminDepartmentController extends Controller
{
    public function __construct(
        private AdminDepartmentService $adminDepartmentService
    ) {}

    /**
     * Admin department list
     *
     * @param AdminDepartmentListRequest $request
     * @return JsonResource
     */
    public function list(AdminDepartmentListRequest $request): JsonResource
    {
        return $this->adminDepartmentService->list($request->all());
    }

    /**
     * Update admin department
     *
     * @param AdminDepartmentUpdateRequest $request
     * @return bool
     */
    public function update(AdminDepartmentUpdateRequest $request): bool
    {
        return $this->adminDepartmentService->update($request->all());
    }
}
