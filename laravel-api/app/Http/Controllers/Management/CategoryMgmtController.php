<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\Category\CategoryMgmtListRequest;
use App\Http\Requests\Management\Category\StoreCategoryMgmtRequest;
use App\Http\Requests\Management\Category\UpdateCategoryMgmtRequest;
use App\Http\Requests\Management\Category\DeleteCategoryMgmtRequest;
use App\Http\Resources\Management\CategoryMgmtResource;
use App\Services\Management\CategoryMgmtService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryMgmtController extends Controller
{
    protected CategoryMgmtService $categoryMgmtService;

    /**
     * CategoryMgmtController constructor
     *
     * @param CategoryMgmtService $categoryMgmtService
     */
    public function __construct(CategoryMgmtService $categoryMgmtService)
    {
        $this->categoryMgmtService = $categoryMgmtService;
    }

    /**
     * Get a listing of categories
     *
     * @param CategoryMgmtListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(CategoryMgmtListRequest $request): AnonymousResourceCollection
    {
        return $this->categoryMgmtService->getAll($request->validated());
    }

    /**
     * Get category by ID
     *
     * @param int $id
     * @return CategoryMgmtResource
     * @throws Exception
     */
    public function show(int $id): CategoryMgmtResource
    {
        return $this->categoryMgmtService->findById($id);
    }

    /**
     * Create a new category
     *
     * @param StoreCategoryMgmtRequest $request
     * @return CategoryMgmtResource
     * @throws Exception
     */
    public function store(StoreCategoryMgmtRequest $request): CategoryMgmtResource
    {
        return $this->categoryMgmtService->create($request->validated());
    }

    /**
     * Update an existing category
     *
     * @param UpdateCategoryMgmtRequest $request
     * @param int $id
     * @return CategoryMgmtResource
     * @throws Exception
     */
    public function update(UpdateCategoryMgmtRequest $request, int $id): CategoryMgmtResource
    {
        return $this->categoryMgmtService->update($id, $request->validated());
    }

    /**
     * Delete a category
     *
     * @param DeleteCategoryMgmtRequest $request
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function destroy(DeleteCategoryMgmtRequest $request, int $id): array
    {
        return $this->categoryMgmtService->delete($id, $request->validated());
    }

    /**
     * Get categories by parent ID
     *
     * @param CategoryMgmtListRequest $request
     * @param int $parentId
     * @return AnonymousResourceCollection
     */
    public function getByParentId(CategoryMgmtListRequest $request, int $parentId): AnonymousResourceCollection
    {
        return $this->categoryMgmtService->getByParentId($parentId, $request->validated());
    }

    /**
     * Get root categories
     *
     * @param CategoryMgmtListRequest $request
     * @return AnonymousResourceCollection
     */
    public function getRootCategories(CategoryMgmtListRequest $request): AnonymousResourceCollection
    {
        return $this->categoryMgmtService->getRootCategories($request->validated());
    }
}
