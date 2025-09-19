<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Controllers\Controller;
use App\Services\History\Master\AdminMstHistService;
use App\Http\Requests\History\Master\AdminMstHist\StoreAdminMstHistRequest;
use App\Http\Requests\History\Master\AdminMstHist\UpdateAdminMstHistRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponse;

class AdminMstHistController extends Controller
{
    use ApiResponse;

    protected AdminMstHistService $adminMstHistService;

    /**
     * Constructor
     *
     * @param AdminMstHistService $adminMstHistService
     */
    public function __construct(AdminMstHistService $adminMstHistService)
    {
        $this->adminMstHistService = $adminMstHistService;
    }

    /**
     * Display a listing of admin history records
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->adminMstHistService->getAll($request->all());
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (\Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Store a newly created admin history record
     *
     * @param StoreAdminMstHistRequest $request
     * @return JsonResponse
     */
    public function store(StoreAdminMstHistRequest $request): JsonResponse
    {
        try {
            $data = $this->adminMstHistService->create($request->validated());
            return self::renderResponse($data, [false, 201, 'Admin history created successfully']);
        } catch (\Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Display the specified admin history record
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data = $this->adminMstHistService->getById($id);
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Admin history record not found']);
        } catch (\Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Update the specified admin history record
     *
     * @param UpdateAdminMstHistRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateAdminMstHistRequest $request, int $id): JsonResponse
    {
        try {
            $data = $this->adminMstHistService->update($request->validated(), $id);
            return self::renderResponse($data, [false, 200, 'Admin history updated successfully']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Admin history record not found']);
        } catch (\Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Remove the specified admin history record
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->adminMstHistService->delete($id);
            return self::renderResponse(['deleted' => $result], [false, 200, 'Admin history deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Admin history record not found']);
        } catch (\Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }
}
