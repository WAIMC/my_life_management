<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Requests\History\Management\CategoryMgmtHist\ListCategoryMgmtHistRequest;
use App\Http\Requests\History\Management\CategoryMgmtHist\StoreCategoryMgmtHistRequest;
use App\Http\Requests\History\Management\CategoryMgmtHist\UpdateCategoryMgmtHistRequest;
use App\Http\Requests\History\Management\CategoryMgmtHist\DeleteCategoryMgmtHistRequest;
use App\Services\History\Management\CategoryMgmtHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryMgmtHistController extends Controller
{
    public function __construct(
        protected CategoryMgmtHistService $categoryMgmtHist
    )
    {
    }
    
    /**
     * CategoryMgmtHist list
     *
     * @param ListCategoryMgmtHistRequest $request
     * @return JsonResource
     */
    public function list(ListCategoryMgmtHistRequest $request): JsonResource
    {
        return $this->categoryMgmtHist->list($request->all());
    }

    /**
     * Store category mgmt hist
     *
     * @param StoreCategoryMgmtHistRequest $request
     * @return int
     */
    public function store(StoreCategoryMgmtHistRequest $request): int
    {
        return $this->categoryMgmtHist->store($request->all());
    }

    /**
     * Update category mgmt hist
     *
     * @param UpdateCategoryMgmtHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateCategoryMgmtHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->categoryMgmtHist->update($payload);
    }

    /**
     * Delete category mgmt hist
     *
     * @param DeleteCategoryMgmtHistRequest $request
     * @return void
     */
    public function delete(DeleteCategoryMgmtHistRequest $request): void
    {
        $this->categoryMgmtHist->delete($request->all());
    }
}
