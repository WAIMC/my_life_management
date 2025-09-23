<?php

namespace App\Services\Management;

use App\Http\Resources\Management\ProductMgmtResource;
use App\Interfaces\Management\ProductMgmtInterface;
use App\Interfaces\Management\CategoryMgmtInterface;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductMgmtService
{
    Use ApiResponse;

    /**
     * @var ProductMgmtInterface
     */
    protected ProductMgmtInterface $productMgmtRepository;

    /**
     * @var CategoryMgmtInterface
     */
    protected CategoryMgmtInterface $categoryMgmtRepository;

    /**
     * ProductMgmtService constructor.
     *
     * @param ProductMgmtInterface $productMgmtRepository
     * @param CategoryMgmtInterface $categoryMgmtRepository
     */
    public function __construct(
        ProductMgmtInterface $productMgmtRepository,
        CategoryMgmtInterface $categoryMgmtRepository
    ) {
        $this->productMgmtRepository = $productMgmtRepository;
        $this->categoryMgmtRepository = $categoryMgmtRepository;
    }

    /**
     * Get all products with optional filtering
     *
     * @param array $payload
     * @return JsonResponse
     */
    public function getAll(array $payload): JsonResponse
    {
        try {
            $products = $this->productMgmtRepository->getAll($payload);
            return $this->success(
                ProductMgmtResource::collection($products),
                'Products retrieved successfully'
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Get product by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getById(int $id): JsonResponse
    {
        try {
            $product = $this->productMgmtRepository->getById($id);
            return $this->success(
                new ProductMgmtResource($product),
                'Product retrieved successfully'
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 404);
        }
    }

    /**
     * Create a new product
     *
     * @param array $payload
     * @return JsonResponse
     */
    public function create(array $payload): JsonResponse
    {
        try {
            // Check if category exists
            $this->categoryMgmtRepository->getById($payload['category_id']);

            DB::beginTransaction();
            $product = $this->productMgmtRepository->create($payload);
            DB::commit();

            return $this->success(
                new ProductMgmtResource($product),
                'Product created successfully',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Update an existing product
     *
     * @param array $payload
     * @param int $id
     * @return JsonResponse
     */
    public function update(array $payload, int $id): JsonResponse
    {
        try {
            // Check if category exists when category_id is provided
            if (isset($payload['category_id'])) {
                $this->categoryMgmtRepository->getById($payload['category_id']);
            }

            DB::beginTransaction();
            $product = $this->productMgmtRepository->update($payload, $id);
            DB::commit();

            return $this->success(
                new ProductMgmtResource($product),
                'Product updated successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Delete a product
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->productMgmtRepository->delete($id);
            DB::commit();

            return $this->success(null, 'Product deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }
}
