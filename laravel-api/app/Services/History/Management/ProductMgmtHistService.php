<?php

namespace App\Services\History\Management;

use App\Http\Resources\History\Management\ProductMgmtHistResource;
use App\Interfaces\History\Management\ProductMgmtHistInterface;
use App\Interfaces\Management\ProductMgmtInterface;
use App\Interfaces\Management\CategoryMgmtInterface;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductMgmtHistService
{
    use ApiResponse;

    /**
     * @var ProductMgmtHistInterface
     */
    protected ProductMgmtHistInterface $productMgmtHistRepository;

    /**
     * @var ProductMgmtInterface
     */
    protected ProductMgmtInterface $productMgmtRepository;

    /**
     * @var CategoryMgmtInterface
     */
    protected CategoryMgmtInterface $categoryMgmtRepository;

    /**
     * ProductMgmtHistService constructor.
     *
     * @param ProductMgmtHistInterface $productMgmtHistRepository
     * @param ProductMgmtInterface $productMgmtRepository
     * @param CategoryMgmtInterface $categoryMgmtRepository
     */
    public function __construct(
        ProductMgmtHistInterface $productMgmtHistRepository,
        ProductMgmtInterface $productMgmtRepository,
        CategoryMgmtInterface $categoryMgmtRepository
    ) {
        $this->productMgmtHistRepository = $productMgmtHistRepository;
        $this->productMgmtRepository = $productMgmtRepository;
        $this->categoryMgmtRepository = $categoryMgmtRepository;
    }

    /**
     * Get all product history records with optional filtering
     *
     * @param array $payload
     * @return JsonResponse
     */
    public function getAll(array $payload): JsonResponse
    {
        try {
            $productHistories = $this->productMgmtHistRepository->getAll($payload);
            return $this->success(
                ProductMgmtHistResource::collection($productHistories),
                'Product history records retrieved successfully'
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Get product history record by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getById(int $id): JsonResponse
    {
        try {
            $productHistory = $this->productMgmtHistRepository->getById($id);
            return $this->success(
                new ProductMgmtHistResource($productHistory),
                'Product history record retrieved successfully'
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 404);
        }
    }

    /**
     * Create a new product history record
     *
     * @param array $payload
     * @return JsonResponse
     */
    public function create(array $payload): JsonResponse
    {
        try {
            // Check if product exists
            $this->productMgmtRepository->getById($payload['product_mgmt_id']);

            // Check if category exists when category_id is provided
            if (isset($payload['category_id'])) {
                $this->categoryMgmtRepository->getById($payload['category_id']);
            }

            DB::beginTransaction();
            $productHistory = $this->productMgmtHistRepository->create($payload);
            DB::commit();

            return $this->success(
                new ProductMgmtHistResource($productHistory),
                'Product history record created successfully',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Update an existing product history record
     *
     * @param array $payload
     * @param int $id
     * @return JsonResponse
     */
    public function update(array $payload, int $id): JsonResponse
    {
        try {
            // Check if product exists when product_mgmt_id is provided
            if (isset($payload['product_mgmt_id'])) {
                $this->productMgmtRepository->getById($payload['product_mgmt_id']);
            }

            // Check if category exists when category_id is provided
            if (isset($payload['category_id'])) {
                $this->categoryMgmtRepository->getById($payload['category_id']);
            }

            DB::beginTransaction();
            $productHistory = $this->productMgmtHistRepository->update($payload, $id);
            DB::commit();

            return $this->success(
                new ProductMgmtHistResource($productHistory),
                'Product history record updated successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Delete a product history record
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->productMgmtHistRepository->delete($id);
            DB::commit();

            return $this->success(null, 'Product history record deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }
}
