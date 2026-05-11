<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Requests\History\Master\PolicyDepartmentMstHist\ListPolicyDepartmentMstHistRequest;
use App\Http\Requests\History\Master\PolicyDepartmentMstHist\StorePolicyDepartmentMstHistRequest;
use App\Http\Requests\History\Master\PolicyDepartmentMstHist\UpdatePolicyDepartmentMstHistRequest;
use App\Http\Requests\History\Master\PolicyDepartmentMstHist\DeletePolicyDepartmentMstHistRequest;
use App\Services\History\Master\PolicyDepartmentMstHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyDepartmentMstHistController extends Controller
{
    public function __construct(
        protected PolicyDepartmentMstHistService $policyDepartmentMstHist
    )
    {
    }
    
    /**
     * PolicyDepartmentMstHist list
     *
     * @param ListPolicyDepartmentMstHistRequest $request
     * @return JsonResource
     */
    public function list(ListPolicyDepartmentMstHistRequest $request): JsonResource
    {
        return $this->policyDepartmentMstHist->list($request->all());
    }

    /**
     * Store policy department mst hist
     *
     * @param StorePolicyDepartmentMstHistRequest $request
     * @return int
     */
    public function store(StorePolicyDepartmentMstHistRequest $request): int
    {
        return $this->policyDepartmentMstHist->store($request->all());
    }

    /**
     * Update policy department mst hist
     *
     * @param UpdatePolicyDepartmentMstHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdatePolicyDepartmentMstHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->policyDepartmentMstHist->update($payload);
    }

    /**
     * Delete policy department mst hist
     *
     * @param DeletePolicyDepartmentMstHistRequest $request
     * @return void
     */
    public function delete(DeletePolicyDepartmentMstHistRequest $request): void
    {
        $this->policyDepartmentMstHist->delete($request->all());
    }
}
