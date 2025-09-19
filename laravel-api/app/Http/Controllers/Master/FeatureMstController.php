<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\FeatureMstService;
use App\Http\Requests\Master\Feature\StoreFeatureMstRequest;
use App\Http\Requests\Master\Feature\UpdateFeatureMstRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponse;

class FeatureMstController extends Controller
{
    use ApiResponse;

    protected FeatureMstService $featureMstService;

    /**
     * Constructor
     *
     * @param FeatureMstService $featureMstService
     */
    public function __construct(FeatureMstService $featureMstService)
    {
        $this->featureMstService = $featureMstService;
    }

    /**
     * Display a listing of features
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->featureMstService->getAll($request->all());
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (\Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Store a newly created feature
     *
     * @param StoreFeatureMstRequest $request
     * @return JsonResponse
     */
    public function store(StoreFeatureMstRequest $request): JsonResponse
    {
        try {
            $data = $this->featureMstService->create($request->validated());
            return self::renderResponse($data, [false, 201, 'Feature created successfully']);
        } catch (\Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Display the specified feature
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $data = $this->featureMstService->getById($id);
            return self::renderResponse($data, [false, 200, 'Success']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Feature not found']);
        } catch (\Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Update the specified feature
     *
     * @param UpdateFeatureMstRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateFeatureMstRequest $request, int $id): JsonResponse
    {
        try {
            $data = $this->featureMstService->update($request->validated(), $id);
            return self::renderResponse($data, [false, 200, 'Feature updated successfully']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Feature not found']);
        } catch (\Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }

    /**
     * Remove the specified feature
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->featureMstService->delete($id);
            return self::renderResponse(['deleted' => $result], [false, 200, 'Feature deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return self::renderResponse(null, [true, 404, 'Feature not found']);
        } catch (\Exception $e) {
            return self::renderResponse(null, [true, 500, $e->getMessage()]);
        }
    }
}
