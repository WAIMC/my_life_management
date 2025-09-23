<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\CategoryMgmtHistInterface;
use App\Http\Resources\History\Management\CategoryMgmtHistResource;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class CategoryMgmtHistService
{
    protected CategoryMgmtHistInterface $categoryMgmtHistRepository;

    /**
     * CategoryMgmtHistService constructor
     *
     * @param CategoryMgmtHistInterface $categoryMgmtHistRepository
     */
    public function __construct(CategoryMgmtHistInterface $categoryMgmtHistRepository)
    {
        $this->categoryMgmtHistRepository = $categoryMgmtHistRepository;
    }

    /**
     * Get all category history records with filtering
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $categoryHistories = $this->categoryMgmtHistRepository->getAll($payload);
        return CategoryMgmtHistResource::collection($categoryHistories);
    }

    /**
     * Get category history by ID
     *
     * @param int $id
     * @return CategoryMgmtHistResource
     * @throws Exception
     */
    public function findById(int $id): CategoryMgmtHistResource
    {
        $categoryHistory = $this->categoryMgmtHistRepository->findById($id);

        if (!$categoryHistory) {
            throw new Exception("Category history record not found", 404);
        }

        return new CategoryMgmtHistResource($categoryHistory);
    }

    /**
     * Get category history by category ID
     *
     * @param int $categoryId
     * @return AnonymousResourceCollection
     */
    public function findByCategoryId(int $categoryId): AnonymousResourceCollection
    {
        $categoryHistories = $this->categoryMgmtHistRepository->findByCategoryId($categoryId);
        return CategoryMgmtHistResource::collection($categoryHistories);
    }

    /**
     * Create new category history record
     *
     * @param array $payload
     * @return CategoryMgmtHistResource
     * @throws Exception
     */
    public function create(array $payload): CategoryMgmtHistResource
    {
        try {
            DB::beginTransaction();

            // Validate category exists
            if (!DB::table('category_mgmt')->where('id', $payload['category_mgmt_id'])->exists()) {
                throw new Exception("Category not found", 404);
            }

            $categoryHistory = $this->categoryMgmtHistRepository->create($payload);

            DB::commit();
            return new CategoryMgmtHistResource($categoryHistory);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
