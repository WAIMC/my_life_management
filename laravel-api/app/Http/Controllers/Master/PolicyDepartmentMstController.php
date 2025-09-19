<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\PolicyDepartmentMstService;
use App\Http\Requests\Master\PolicyDepartment\StorePolicyDepartmentMstRequest;
use App\Http\Requests\Master\PolicyDepartment\UpdatePolicyDepartmentMstRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;

class PolicyDepartmentMstController extends Controller
{
    use ApiResponse;

    protected PolicyDepartmentMstService $policyDepartmentMstService;

    /**
     * Constructor
     *
     * @param PolicyDepartmentMstService $policyDepartmentMstService
     */
    public function __construct(PolicyDepartmentMstService $policyDepartmentMstService)
    {
        $this->policyDepartmentMstService = $policyDepartmentMstService;
    }

    /**
     * Display a listing of policy departments
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->policyDepartmentMstService->getAll($request->all());
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Store a newly created policy department
     *
     * @param StorePolicyDepartmentMstRequest $request
     * @return JsonResponse
     */
    public function store(StorePolicyDepartmentMstRequest $request): JsonResponse
    {
        try {
            $data = $this->policyDepartmentMstService->create($request->validated());
            return self::renderResponse($data, [false, 201, 'Policy department created successfully']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Display the specified policy department
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data = $this->policyDepartmentMstService->getById($id);
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Policy department not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Update the specified policy department
     *
     * @param UpdatePolicyDepartmentMstRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdatePolicyDepartmentMstRequest $request, int $id): JsonResponse
    {
        try {
            $data = $this->policyDepartmentMstService->update($request->validated(), $id);
            return self::renderResponse($data, [false, 200, 'Policy department updated successfully']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Policy department not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Remove the specified policy department
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->policyDepartmentMstService->delete($id);
            return self::renderResponse(['deleted' => $result], [false, 200, 'Policy department deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Policy department not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Get policy departments by table name
     *
     * @param string $tableName
     * @return JsonResponse
     */
    public function getByTableName(string $tableName): JsonResponse
    {
        try {
            $data = $this->policyDepartmentMstService->getByTableName($tableName);
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Get policy department by table name and row ID
     *
     * @param string $tableName
     * @param int $rowId
     * @return JsonResponse
     */
    public function getByTableNameAndRowId(string $tableName, int $rowId): JsonResponse
    {
        try {
            $data = $this->policyDepartmentMstService->getByTableNameAndRowId($tableName, $rowId);
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Policy department not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }
}
