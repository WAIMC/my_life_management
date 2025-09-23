<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Management\Product\ProductMgmtHistListRequest;
use App\Http\Requests\History\Management\Product\StoreProductMgmtHistRequest;
use App\Http\Requests\History\Management\Product\UpdateProductMgmtHistRequest;
use App\Services\History\Management\ProductMgmtHistService;
use Illuminate\Http\JsonResponse;

class ProductMgmtHistController extends Controller
{
    /**
     * @var ProductMgmtHistService
     */
    protected ProductMgmtHistService $productMgmtHistService;

    /**
     * ProductMgmtHistController constructor.
     *
     * @param ProductMgmtHistService $productMgmtHistService
     */
    public function __construct(ProductMgmtHistService $productMgmtHistService)
    {
        $this->productMgmtHistService = $productMgmtHistService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ProductMgmtHistListRequest $request
     * @return JsonResponse
     */
    public function index(ProductMgmtHistListRequest $request): JsonResponse
    {
        return $this->productMgmtHistService->getAll($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductMgmtHistRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductMgmtHistRequest $request): JsonResponse
    {
        return $this->productMgmtHistService->create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->productMgmtHistService->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductMgmtHistRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateProductMgmtHistRequest $request, int $id): JsonResponse
    {
        return $this->productMgmtHistService->update($request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->productMgmtHistService->delete($id);
    }
}
