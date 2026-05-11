<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\AdminRoleMst\ListAdminRoleMstRequest;
use App\Http\Requests\Master\AdminRoleMst\UpdateAdminRoleMstRequest;
use App\Services\Master\AdminRoleMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminRoleMstController extends Controller
{
    public function __construct(
        protected AdminRoleMstService $adminRoleMst
    )
    {
    }
    
    /**
     * AdminRoleMst list
     *
     * @param ListAdminRoleMstRequest $request
     * @return JsonResource
     */
    public function list(ListAdminRoleMstRequest $request): JsonResource
    {
        return $this->adminRoleMst->list($request->all());
    }

    /**
     * Update admin role mst
     *
     * @param UpdateAdminRoleMstRequest $request
     * @return bool
     */
    public function update(UpdateAdminRoleMstRequest $request): bool
    {
        return $this->adminRoleMst->update($request->all());
    }
}
