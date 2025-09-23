<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Management\Category\CategoryMgmtHistListRequest;
use App\Http\Requests\History\Management\Category\StoreCategoryMgmtHistRequest;
use App\Services\Management\CategoryMgmtHistService;
use Exception;
use Illuminate\Http\JsonResponse;

class CategoryMgmtHistController extends Controller
{
    protected CategoryMgmtHistService $categoryMgmtHistService;

    /**
     * CategoryMgmtHistController constructor
     *
     * @param CategoryMgmtHistService $categoryMgmtHistService
     */
    public function __construct(CategoryMgmtHistService $categoryMgmtHistService)
    {
        $this->categoryMgmtHistService = $categoryMgmtHistService;
    }

    /**
     * Get a listing of category histories
     *
     * @param CategoryMgmtHistListRequest $request
     * @return JsonResponse
     */
    public function index(CategoryMgmtHistListRequest $request): JsonResponse
    {
        return $this->categoryMgmtHistService->getAll($request->validated());
    }

    /**
     * Get category history by ID
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show(int $id): JsonResponse
    {
        return $this->categoryMgmtHistService->findById($id);
    }

    /**
     * Get category history by category ID
     *
     * @param int $categoryId
     * @return JsonResponse
     */
    public function getByCategoryId(int $categoryId): JsonResponse
    {
        return $this->categoryMgmtHistService->findByCategoryId($categoryId);
    }

    /**
     * Create a new category history record
     *
     * @param StoreCategoryMgmtHistRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreCategoryMgmtHistRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_at'] = now()->format('Y-m-d H:i:s');

        return $this->categoryMgmtHistService->create($data);
    }
}
