<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\AdminRole\AdminRoleListRequest;
use App\Http\Requests\Master\AdminRole\AdminRoleUpdateRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;
use App\Services\Master\AdminRoleService;

class AdminRoleController extends Controller
{
    public function __construct(
        private AdminRoleService $adminRoleService
    )
    {
    }

    /**
     * Admin role list
     *
     * @param AdminRoleListRequest $request
     * @return JsonResource
     */
    public function list(AdminRoleListRequest $request): JsonResource
    {
        return $this->adminRoleService->list($request->all());
    }

    /**
     * Update admin role
     *
     * @param AdminRoleUpdateRequest $request
     * @return bool
     */
    public function update(AdminRoleUpdateRequest $request): bool
    {
        return $this->adminRoleService->update($request->all());
    }
}
