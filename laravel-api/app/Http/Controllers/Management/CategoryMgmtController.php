<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\Category\CategoryMgmtListRequest;
use App\Http\Requests\Management\Category\StoreCategoryMgmtRequest;
use App\Http\Requests\Management\Category\UpdateCategoryMgmtRequest;
use App\Http\Requests\Management\Category\DeleteCategoryMgmtRequest;
use App\Services\Management\CategoryMgmtService;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(CategoryMgmtListRequest $request)
    {
        return $this->categoryMgmtService->getAll($request->validated());
    }

    /**
     * Get category by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return $this->categoryMgmtService->findById($id);
    }

    /**
     * Create a new category
     *
     * @param StoreCategoryMgmtRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryMgmtRequest $request)
    {
        return $this->categoryMgmtService->create($request->validated());
    }

    /**
     * Update an existing category
     *
     * @param UpdateCategoryMgmtRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCategoryMgmtRequest $request, $id)
    {
        return $this->categoryMgmtService->update($id, $request->validated());
    }

    /**
     * Delete a category
     *
     * @param DeleteCategoryMgmtRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteCategoryMgmtRequest $request, $id)
    {
        return $this->categoryMgmtService->delete($id, $request->validated());
    }

    /**
     * Get categories by parent ID
     *
     * @param CategoryMgmtListRequest $request
     * @param int $parentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByParentId(CategoryMgmtListRequest $request, $parentId)
    {
        return $this->categoryMgmtService->getByParentId($parentId, $request->validated());
    }

    /**
     * Get root categories
     *
     * @param CategoryMgmtListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRootCategories(CategoryMgmtListRequest $request)
    {
        return $this->categoryMgmtService->getRootCategories($request->validated());
    }
}
