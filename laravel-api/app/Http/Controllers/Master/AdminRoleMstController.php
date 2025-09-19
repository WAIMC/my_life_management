<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\AdminRole\AdminRoleMstListRequest;
use App\Http\Requests\Master\AdminRole\AdminRoleMstUpdateRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;
use App\Services\Master\AdminRoleMstService;

class AdminRoleMstController extends Controller
{
    public function __construct(
        private AdminRoleMstService $adminRoleService
    )
    {
    }

    /**
     * AdminMst role list
     *
     * @param AdminRoleMstListRequest $request
     * @return JsonResource
     */
    public function list(AdminRoleMstListRequest $request): JsonResource
    {
        return $this->adminRoleService->list($request->all());
    }

    /**
     * Update admin role
     *
     * @param AdminRoleMstUpdateRequest $request
     * @return bool
     */
    public function update(AdminRoleMstUpdateRequest $request): bool
    {
        return $this->adminRoleService->update($request->all());
    }
}
