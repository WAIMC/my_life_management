<?php

namespace App\Services\Management;

use App\Enums\ActionType;
use App\Interfaces\Management\CategoryMgmtInterface;
use App\Http\Resources\Management\CategoryMgmtResource;
use App\Repositories\History\Management\CategoryMgmtHistRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class CategoryMgmtService
{
    protected CategoryMgmtInterface $categoryMgmtRepository;
    protected CategoryMgmtHistRepository $categoryMgmtHistRepository;

    /**
     * CategoryMgmtService constructor
     *
     * @param CategoryMgmtInterface $categoryMgmtRepository
     * @param CategoryMgmtHistRepository $categoryMgmtHistRepository
     */
    public function __construct(
        CategoryMgmtInterface $categoryMgmtRepository,
        CategoryMgmtHistRepository $categoryMgmtHistRepository
    ) {
        $this->categoryMgmtRepository = $categoryMgmtRepository;
        $this->categoryMgmtHistRepository = $categoryMgmtHistRepository;
    }

    /**
     * Get all categories with filtering and pagination
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $categories = $this->categoryMgmtRepository->getAll($payload);
        return CategoryMgmtResource::collection($categories);
    }

    /**
     * Get category by ID
     *
     * @param int $id
     * @return CategoryMgmtResource
     * @throws Exception
     */
    public function findById(int $id): CategoryMgmtResource
    {
        $category = $this->categoryMgmtRepository->findById($id);

        if (!$category) {
            throw new Exception("Category not found", 404);
        }

        return new CategoryMgmtResource($category);
    }

    /**
     * Create new category
     *
     * @param array $payload
     * @return CategoryMgmtResource
     * @throws Exception
     */
    public function create(array $payload): CategoryMgmtResource
    {
        try {
            DB::beginTransaction();

            // Validate parent_id exists if not 0
            if (isset($payload['parent_id']) && $payload['parent_id'] > 0) {
                $parentCategory = $this->categoryMgmtRepository->findById($payload['parent_id']);
                if (!$parentCategory) {
                    throw new Exception("Parent category not found", 404);
                }
            }

            $category = $this->categoryMgmtRepository->create($payload);

            // Create history record
            if (isset($payload['author_id'])) {
                $historyData = [
                    'category_mgmt_id' => $category->id,
                    'parent_id' => $category->parent_id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'status' => $category->status,
                    'is_display' => $category->is_display,
                    'rank_order' => $category->rank_order,
                    'action' => ActionType::CREATE,
                    'author_id' => $payload['author_id'],
                    'created_at' => now()->format('Y-m-d H:i:s')
                ];

                $this->categoryMgmtHistRepository->create($historyData);
            }

            DB::commit();
            return new CategoryMgmtResource($category);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update category by ID
     *
     * @param int $id
     * @param array $payload
     * @return CategoryMgmtResource
     * @throws Exception
     */
    public function update(int $id, array $payload): CategoryMgmtResource
    {
        try {
            DB::beginTransaction();

            $category = $this->categoryMgmtRepository->findById($id);

            if (!$category) {
                throw new Exception("Category not found", 404);
            }

            // Validate parent_id exists if not 0
            if (isset($payload['parent_id']) && $payload['parent_id'] > 0) {
                if ($payload['parent_id'] == $id) {
                    throw new Exception("Category cannot be its own parent", 400);
                }

                $parentCategory = $this->categoryMgmtRepository->findById($payload['parent_id']);
                if (!$parentCategory) {
                    throw new Exception("Parent category not found", 404);
                }
            }

            $updatedCategory = $this->categoryMgmtRepository->update($id, $payload);

            // Create history record
            if (isset($payload['author_id'])) {
                $historyData = [
                    'category_mgmt_id' => $updatedCategory->id,
                    'parent_id' => $updatedCategory->parent_id,
                    'name' => $updatedCategory->name,
                    'slug' => $updatedCategory->slug,
                    'description' => $updatedCategory->description,
                    'status' => $updatedCategory->status,
                    'is_display' => $updatedCategory->is_display,
                    'rank_order' => $updatedCategory->rank_order,
                    'action' => ActionType::UPDATE,
                    'author_id' => $payload['author_id'],
                    'created_at' => now()->format('Y-m-d H:i:s')
                ];

                $this->categoryMgmtHistRepository->create($historyData);
            }

            DB::commit();
            return new CategoryMgmtResource($updatedCategory);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete category by ID
     *
     * @param int $id
     * @param array $payload
     * @return array
     * @throws Exception
     */
    public function delete(int $id, array $payload): array
    {
        try {
            DB::beginTransaction();

            $category = $this->categoryMgmtRepository->findById($id);

            if (!$category) {
                throw new Exception("Category not found", 404);
            }

            // Check if this category has child categories
            if ($category->children->count() > 0) {
                throw new Exception("Cannot delete category with child categories", 400);
            }

            // Check if category has associated products
            if ($category->products->count() > 0) {
                throw new Exception("Cannot delete category with associated products", 400);
            }

            // Check if category has associated projects
            if ($category->projects->count() > 0) {
                throw new Exception("Cannot delete category with associated projects", 400);
            }

            // Store category data for history before deletion
            $categoryData = [
                'parent_id' => $category->parent_id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'status' => $category->status,
                'is_display' => $category->is_display,
                'rank_order' => $category->rank_order,
            ];

            $this->categoryMgmtRepository->delete($id);

            // Create history record
            if (isset($payload['author_id'])) {
                $historyData = array_merge($categoryData, [
                    'category_mgmt_id' => $id,
                    'action' => ActionType::DELETE,
                    'author_id' => $payload['author_id'],
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);

                $this->categoryMgmtHistRepository->create($historyData);
            }

            DB::commit();
            return ['success' => true, 'message' => 'Category deleted successfully'];
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get categories by parent ID
     *
     * @param int $parentId
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getByParentId(int $parentId, array $payload): AnonymousResourceCollection
    {
        $categories = $this->categoryMgmtRepository->getByParentId($parentId, $payload);
        return CategoryMgmtResource::collection($categories);
    }

    /**
     * Get root categories
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getRootCategories(array $payload): AnonymousResourceCollection
    {
        $categories = $this->categoryMgmtRepository->getRootCategories($payload);
        return CategoryMgmtResource::collection($categories);
    }
}
