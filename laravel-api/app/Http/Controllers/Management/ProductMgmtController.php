<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\Product\ProductMgmtListRequest;
use App\Http\Requests\Management\Product\StoreProductMgmtRequest;
use App\Http\Requests\Management\Product\UpdateProductMgmtRequest;
use App\Services\Management\ProductMgmtService;
use Illuminate\Http\JsonResponse;

class ProductMgmtController extends Controller
{
    /**
     * @var ProductMgmtService
     */
    protected ProductMgmtService $productMgmtService;

    /**
     * ProductMgmtController constructor.
     *
     * @param ProductMgmtService $productMgmtService
     */
    public function __construct(ProductMgmtService $productMgmtService)
    {
        $this->productMgmtService = $productMgmtService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ProductMgmtListRequest $request
     * @return JsonResponse
     */
    public function index(ProductMgmtListRequest $request): JsonResponse
    {
        return $this->productMgmtService->getAll($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductMgmtRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductMgmtRequest $request): JsonResponse
    {
        return $this->productMgmtService->create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->productMgmtService->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductMgmtRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateProductMgmtRequest $request, int $id): JsonResponse
    {
        return $this->productMgmtService->update($request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->productMgmtService->delete($id);
    }
}
