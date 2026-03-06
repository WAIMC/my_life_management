<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\CategoryEntryMgmt\ListCategoryEntryMgmtRequest;
use App\Http\Requests\Management\CategoryEntryMgmt\UpdateCategoryEntryMgmtRequest;
use App\Services\Management\CategoryEntryMgmtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryEntryMgmtController extends Controller
{
    public function __construct(
        protected CategoryEntryMgmtService $categoryEntryMgmt
    )
    {
    }
    
    /**
     * CategoryEntryMgmt list
     *
     * @param ListCategoryEntryMgmtRequest $request
     * @return JsonResource
     */
    public function list(ListCategoryEntryMgmtRequest $request): JsonResource
    {
        return $this->categoryEntryMgmt->list($request->all());
    }

    /**
     * Update category entry mgmt
     *
     * @param UpdateCategoryEntryMgmtRequest $request
     * @return bool
     */
    public function update(UpdateCategoryEntryMgmtRequest $request): bool
    {
        return $this->categoryEntryMgmt->update($request->all());
    }
}
