<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\CategorySkill\CategorySkillMgmtListRequest;
use App\Http\Requests\Management\CategorySkill\AttachSkillRequest;
use App\Http\Requests\Management\CategorySkill\SyncSkillsRequest;
use App\Services\Management\CategorySkillMgmtService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategorySkillMgmtController extends Controller
{
    protected CategorySkillMgmtService $categorySkillMgmtService;

    /**
     * CategorySkillMgmtController constructor
     *
     * @param CategorySkillMgmtService $categorySkillMgmtService
     */
    public function __construct(CategorySkillMgmtService $categorySkillMgmtService)
    {
        $this->categorySkillMgmtService = $categorySkillMgmtService;
    }

    /**
     * Get all category-skill relationships
     *
     * @param CategorySkillMgmtListRequest $request
     * @return JsonResponse
     */
    public function index(CategorySkillMgmtListRequest $request): JsonResponse
    {
        return $this->categorySkillMgmtService->getAll($request->validated());
    }

    /**
     * Get skills by category ID
     *
     * @param int $categoryId
     * @return JsonResponse
     * @throws Exception
     */
    public function getSkillsByCategoryId(int $categoryId): JsonResponse
    {
        return $this->categorySkillMgmtService->getSkillsByCategoryId($categoryId);
    }

    /**
     * Get categories by skill ID
     *
     * @param int $skillId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function getCategoriesBySkillId(int $skillId): AnonymousResourceCollection
    {
        return $this->categorySkillMgmtService->getCategoriesBySkillId($skillId);
    }

    /**
     * Attach a skill to a category
     *
     * @param AttachSkillRequest $request
     * @param int $categoryId
     * @return array
     * @throws Exception
     */
    public function attachSkill(AttachSkillRequest $request, int $categoryId): array
    {
        $skillId = $request->validated()['skill_id'];
        return $this->categorySkillMgmtService->attachSkill($categoryId, $skillId);
    }

    /**
     * Detach a skill from a category
     *
     * @param int $categoryId
     * @param int $skillId
     * @return array
     * @throws Exception
     */
    public function detachSkill(int $categoryId, int $skillId): array
    {
        return $this->categorySkillMgmtService->detachSkill($categoryId, $skillId);
    }

    /**
     * Sync skills for a category
     *
     * @param SyncSkillsRequest $request
     * @param int $categoryId
     * @return JsonResponse
     * @throws Exception
     */
    public function syncSkills(SyncSkillsRequest $request, int $categoryId): JsonResponse
    {
        $skillIds = $request->validated()['skill_ids'];
        return $this->categorySkillMgmtService->syncSkills($categoryId, $skillIds);
    }
}
