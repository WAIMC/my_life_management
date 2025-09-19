<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\DepartmentManagementMstService;
use App\Http\Requests\Master\DepartmentManagement\StoreDepartmentManagementMstRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponse;

class DepartmentManagementMstController extends Controller
{
    use ApiResponse;

    protected DepartmentManagementMstService $departmentManagementMstService;

    /**
     * Constructor
     *
     * @param DepartmentManagementMstService $departmentManagementMstService
     */
    public function __construct(DepartmentManagementMstService $departmentManagementMstService)
    {
        $this->departmentManagementMstService = $departmentManagementMstService;
    }

    /**
     * Display a listing of department management relations
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->departmentManagementMstService->getAll($request->all());
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Store a newly created department management relation
     *
     * @param StoreDepartmentManagementMstRequest $request
     * @return JsonResponse
     */
    public function store(StoreDepartmentManagementMstRequest $request): JsonResponse
    {
        try {
            $data = $this->departmentManagementMstService->create($request->validated());
            return self::renderResponse($data, [false, 201, 'Department management relation created successfully']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Display the specified department management relation
     *
     * @param int $departmentId
     * @param int $policyDepartmentId
     * @return JsonResponse
     */
    public function show(int $departmentId, int $policyDepartmentId): JsonResponse
    {
        try {
            $data = $this->departmentManagementMstService->getById($departmentId, $policyDepartmentId);
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Department management relation not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Remove the specified department management relation
     *
     * @param int $departmentId
     * @param int $policyDepartmentId
     * @return JsonResponse
     */
    public function destroy(int $departmentId, int $policyDepartmentId): JsonResponse
    {
        try {
            $result = $this->departmentManagementMstService->delete($departmentId, $policyDepartmentId);
            return self::renderResponse(['deleted' => $result], [false, 200, 'Department management relation deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Department management relation not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Get department management relations by department ID
     *
     * @param int $departmentId
     * @return JsonResponse
     */
    public function getByDepartmentId(int $departmentId): JsonResponse
    {
        try {
            $data = $this->departmentManagementMstService->getByDepartmentId($departmentId);
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Department not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Get department management relations by policy department ID
     *
     * @param int $policyDepartmentId
     * @return JsonResponse
     */
    public function getByPolicyDepartmentId(int $policyDepartmentId): JsonResponse
    {
        try {
            $data = $this->departmentManagementMstService->getByPolicyDepartmentId($policyDepartmentId);
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Policy department not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }
}
