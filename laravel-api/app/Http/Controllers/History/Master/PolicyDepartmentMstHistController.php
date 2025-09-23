<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Master\PolicyDepartment\PolicyDepartmentMstHistListRequest;
use App\Http\Requests\History\Master\PolicyDepartment\StorePolicyDepartmentMstHistRequest;
use App\Http\Requests\History\Master\PolicyDepartment\UpdatePolicyDepartmentMstHistRequest;
use App\Http\Resources\History\Master\PolicyDepartmentMstHistResource;
use App\Services\History\Master\PolicyDepartmentMstHistService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PolicyDepartmentMstHistController extends Controller
{
    /**
     * @var PolicyDepartmentMstHistService
     */
    protected PolicyDepartmentMstHistService $policyDepartmentMstHistService;

    /**
     * PolicyDepartmentMstHistController constructor.
     *
     * @param PolicyDepartmentMstHistService $policyDepartmentMstHistService
     */
    public function __construct(PolicyDepartmentMstHistService $policyDepartmentMstHistService)
    {
        $this->policyDepartmentMstHistService = $policyDepartmentMstHistService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param PolicyDepartmentMstHistListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(PolicyDepartmentMstHistListRequest $request): AnonymousResourceCollection
    {
        return $this->policyDepartmentMstHistService->getAll($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePolicyDepartmentMstHistRequest $request
     * @return PolicyDepartmentMstHistResource
     * @throws Exception
     */
    public function store(StorePolicyDepartmentMstHistRequest $request): PolicyDepartmentMstHistResource
    {
        return $this->policyDepartmentMstHistService->create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return PolicyDepartmentMstHistResource
     */
    public function show(int $id): PolicyDepartmentMstHistResource
    {
        return $this->policyDepartmentMstHistService->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePolicyDepartmentMstHistRequest $request
     * @param int $id
     * @return PolicyDepartmentMstHistResource
     * @throws Exception
     */
    public function update(UpdatePolicyDepartmentMstHistRequest $request, int $id): PolicyDepartmentMstHistResource
    {
        return $this->policyDepartmentMstHistService->update($request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        return $this->policyDepartmentMstHistService->delete($id);
    }
}
