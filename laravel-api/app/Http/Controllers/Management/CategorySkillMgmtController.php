<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\CategorySkillMgmt\ListCategorySkillMgmtRequest;
use App\Http\Requests\Management\CategorySkillMgmt\UpdateCategorySkillMgmtRequest;
use App\Services\Management\CategorySkillMgmtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorySkillMgmtController extends Controller
{
    public function __construct(
        protected CategorySkillMgmtService $categorySkillMgmt
    )
    {
    }
    
    /**
     * CategorySkillMgmt list
     *
     * @param ListCategorySkillMgmtRequest $request
     * @return JsonResource
     */
    public function list(ListCategorySkillMgmtRequest $request): JsonResource
    {
        return $this->categorySkillMgmt->list($request->all());
    }

    /**
     * Update category skill mgmt
     *
     * @param UpdateCategorySkillMgmtRequest $request
     * @return bool
     */
    public function update(UpdateCategorySkillMgmtRequest $request): bool
    {
        return $this->categorySkillMgmt->update($request->all());
    }
}
