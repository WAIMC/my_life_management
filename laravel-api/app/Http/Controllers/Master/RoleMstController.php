<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\RoleMstService;
use App\Http\Requests\Master\Role\StoreRoleMstRequest;
use App\Http\Requests\Master\Role\UpdateRoleMstRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponse;

class RoleMstController extends Controller
{
    use ApiResponse;

    protected RoleMstService $roleMstService;

    /**
     * Constructor
     *
     * @param RoleMstService $roleMstService
     */
    public function __construct(RoleMstService $roleMstService)
    {
        $this->roleMstService = $roleMstService;
    }

    /**
     * Display a listing of roles
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->roleMstService->getAll($request->all());
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Store a newly created role
     *
     * @param StoreRoleMstRequest $request
     * @return JsonResponse
     */
    public function store(StoreRoleMstRequest $request): JsonResponse
    {
        try {
            $data = $this->roleMstService->create($request->validated());
            return self::renderResponse($data, [false, 201, 'Role created successfully']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Display the specified role
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data = $this->roleMstService->getById($id);
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Role not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Update the specified role
     *
     * @param UpdateRoleMstRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateRoleMstRequest $request, int $id): JsonResponse
    {
        try {
            $data = $this->roleMstService->update($request->validated(), $id);
            return self::renderResponse($data, [false, 200, 'Role updated successfully']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Role not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Remove the specified role
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->roleMstService->delete($id);
            return self::renderResponse(['deleted' => $result], [false, 200, 'Role deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Role not found']);
        } catch (Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }
}
