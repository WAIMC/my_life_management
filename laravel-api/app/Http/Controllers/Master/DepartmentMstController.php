<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\DepartmentMstService;
use App\Http\Requests\Master\Department\StoreDepartmentMstRequest;
use App\Http\Requests\Master\Department\UpdateDepartmentMstRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponse;

class DepartmentMstController extends Controller
{
    use ApiResponse;

    protected DepartmentMstService $departmentMstService;

    /**
     * Constructor
     *
     * @param DepartmentMstService $departmentMstService
     */
    public function __construct(DepartmentMstService $departmentMstService)
    {
        $this->departmentMstService = $departmentMstService;
    }

    /**
     * Display a listing of departments
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->departmentMstService->getAll($request->all());
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Store a newly created department
     *
     * @param StoreDepartmentMstRequest $request
     * @return JsonResponse
     */
    public function store(StoreDepartmentMstRequest $request): JsonResponse
    {
        try {
            $data = $this->departmentMstService->create($request->validated());
            return self::renderResponse($data, [false, 201, 'Department created successfully']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Display the specified department
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data = $this->departmentMstService->getById($id);
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Department not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Update the specified department
     *
     * @param UpdateDepartmentMstRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateDepartmentMstRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->departmentMstService->update($request->validated(), $id);
            return self::renderResponse($data, [false, 200, 'Department updated successfully']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Department not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Remove the specified department
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->departmentMstService->delete($id);
            return self::renderResponse(['deleted' => $result], [false, 200, 'Department deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Department not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Get department by code
     *
     * @param string $code
     * @return JsonResponse
     */
    public function getByCode(string $code): JsonResponse
    {
        try {
            $data = $this->departmentMstService->getByCode($code);
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Department not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }
}
