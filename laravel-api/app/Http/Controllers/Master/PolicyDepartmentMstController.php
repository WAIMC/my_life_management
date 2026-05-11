<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\PolicyDepartmentMst\ListPolicyDepartmentMstRequest;
use App\Http\Requests\Master\PolicyDepartmentMst\StorePolicyDepartmentMstRequest;
use App\Http\Requests\Master\PolicyDepartmentMst\UpdatePolicyDepartmentMstRequest;
use App\Http\Requests\Master\PolicyDepartmentMst\DeletePolicyDepartmentMstRequest;
use App\Services\Master\PolicyDepartmentMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyDepartmentMstController extends Controller
{
    public function __construct(
        protected PolicyDepartmentMstService $policyDepartmentMst
    )
    {
    }
    
    /**
     * PolicyDepartmentMst list
     *
     * @param ListPolicyDepartmentMstRequest $request
     * @return JsonResource
     */
    public function list(ListPolicyDepartmentMstRequest $request): JsonResource
    {
        return $this->policyDepartmentMst->list($request->all());
    }

    /**
     * Store policy department mst
     *
     * @param StorePolicyDepartmentMstRequest $request
     * @return int
     */
    public function store(StorePolicyDepartmentMstRequest $request): int
    {
        return $this->policyDepartmentMst->store($request->all());
    }

    /**
     * Update policy department mst
     *
     * @param UpdatePolicyDepartmentMstRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdatePolicyDepartmentMstRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->policyDepartmentMst->update($payload);
    }

    /**
     * Delete policy department mst
     *
     * @param DeletePolicyDepartmentMstRequest $request
     * @return void
     */
    public function delete(DeletePolicyDepartmentMstRequest $request): void
    {
        $this->policyDepartmentMst->delete($request->all());
    }
}
