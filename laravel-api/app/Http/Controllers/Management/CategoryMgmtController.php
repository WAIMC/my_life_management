<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\CategoryMgmt\ListCategoryMgmtRequest;
use App\Http\Requests\Management\CategoryMgmt\StoreCategoryMgmtRequest;
use App\Http\Requests\Management\CategoryMgmt\UpdateCategoryMgmtRequest;
use App\Http\Requests\Management\CategoryMgmt\DeleteCategoryMgmtRequest;
use App\Services\Management\CategoryMgmtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryMgmtController extends Controller
{
    public function __construct(
        protected CategoryMgmtService $categoryMgmt
    )
    {
    }
    
    /**
     * CategoryMgmt list
     *
     * @param ListCategoryMgmtRequest $request
     * @return JsonResource
     */
    public function list(ListCategoryMgmtRequest $request): JsonResource
    {
        return $this->categoryMgmt->list($request->all());
    }

    /**
     * Store category mgmt
     *
     * @param StoreCategoryMgmtRequest $request
     * @return int
     */
    public function store(StoreCategoryMgmtRequest $request): int
    {
        return $this->categoryMgmt->store($request->all());
    }

    /**
     * Update category mgmt
     *
     * @param UpdateCategoryMgmtRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateCategoryMgmtRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->categoryMgmt->update($payload);
    }

    /**
     * Delete category mgmt
     *
     * @param DeleteCategoryMgmtRequest $request
     * @return void
     */
    public function delete(DeleteCategoryMgmtRequest $request): void
    {
        $this->categoryMgmt->delete($request->all());
    }
}
