<?php

namespace App\Services\Management;

use App\Http\Resources\Management\CategorySkillMgmtResource;
use App\Interfaces\Management\CategorySkillMgmtInterface;
use App\Repositories\Management\CategoryMgmtRepository;
use App\Repositories\Management\SkillMgmtRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class CategorySkillMgmtService
{
    protected CategorySkillMgmtInterface $categorySkillMgmtRepository;
    protected CategoryMgmtRepository $categoryMgmtRepository;
    protected SkillMgmtRepository $skillMgmtRepository;

    /**
     * CategorySkillMgmtService constructor
     *
     * @param CategorySkillMgmtInterface $categorySkillMgmtRepository
     * @param CategoryMgmtRepository $categoryMgmtRepository
     * @param SkillMgmtRepository $skillMgmtRepository
     */
    public function __construct(
        CategorySkillMgmtInterface $categorySkillMgmtRepository,
        CategoryMgmtRepository $categoryMgmtRepository,
        SkillMgmtRepository $skillMgmtRepository
    ) {
        $this->categorySkillMgmtRepository = $categorySkillMgmtRepository;
        $this->categoryMgmtRepository = $categoryMgmtRepository;
        $this->skillMgmtRepository = $skillMgmtRepository;
    }

    /**
     * Get all category-skill relationships
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed
    {
        return $this->categorySkillMgmtRepository->getAll($payload);
    }

    /**
     * Get skills by category ID
     *
     * @param int $categoryId
     * @return mixed
     * @throws Exception
     */
    public function getSkillsByCategoryId(int $categoryId): mixed
    {
        // Verify category exists
        $category = $this->categoryMgmtRepository->findById($categoryId);

        if (!$category) {
            throw new Exception("Category not found", 404);
        }

        $skills = $this->categorySkillMgmtRepository->getSkillsByCategoryId($categoryId);
        return SkillMgmtResource::collection($skills);
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
        // Verify skill exists
        $skill = $this->skillMgmtRepository->findById($skillId);

        if (!$skill) {
            throw new Exception("Skill not found", 404);
        }

        $categories = $this->categorySkillMgmtRepository->getCategoriesBySkillId($skillId);
        return CategorySkillMgmtResource::collection($categories);
    }

    /**
     * Attach a skill to a category
     *
     * @param int $categoryId
     * @param int $skillId
     * @return array
     * @throws Exception
     */
    public function attachSkill(int $categoryId, int $skillId): array
    {
        try {
            DB::beginTransaction();

            // Verify category exists
            $category = $this->categoryMgmtRepository->findById($categoryId);
            if (!$category) {
                throw new Exception("Category not found", 404);
            }

            // Verify skill exists
            $skill = $this->skillMgmtRepository->findById($skillId);
            if (!$skill) {
                throw new Exception("Skill not found", 404);
            }

            // Check if already attached
            if ($this->categorySkillMgmtRepository->exists($categoryId, $skillId)) {
                throw new Exception("Skill is already attached to this category", 400);
            }

            $relation = $this->categorySkillMgmtRepository->attachSkill($categoryId, $skillId);

            DB::commit();
            return [
                'success' => true,
                'message' => 'Skill attached to category successfully'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
        try {
            DB::beginTransaction();

            // Verify category exists
            $category = $this->categoryMgmtRepository->findById($categoryId);
            if (!$category) {
                throw new Exception("Category not found", 404);
            }

            // Verify skill exists
            $skill = $this->skillMgmtRepository->findById($skillId);
            if (!$skill) {
                throw new Exception("Skill not found", 404);
            }

            // Check if relationship exists
            if (!$this->categorySkillMgmtRepository->exists($categoryId, $skillId)) {
                throw new Exception("Skill is not attached to this category", 400);
            }

            $this->categorySkillMgmtRepository->detachSkill($categoryId, $skillId);

            DB::commit();
            return [
                'success' => true,
                'message' => 'Skill detached from category successfully'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Sync skills for a category
     *
     * @param int $categoryId
     * @param array $skillIds
     * @return mixed
     * @throws Exception
     */
    public function syncSkills(int $categoryId, array $skillIds): mixed
    {
        try {
            DB::beginTransaction();

            // Verify category exists
            $category = $this->categoryMgmtRepository->findById($categoryId);
            if (!$category) {
                throw new Exception("Category not found", 404);
            }

            // Verify all skills exist
            foreach ($skillIds as $skillId) {
                $skill = $this->skillMgmtRepository->findById($skillId);
                if (!$skill) {
                    throw new Exception("Skill with ID {$skillId} not found", 404);
                }
            }

            $skills = $this->categorySkillMgmtRepository->syncSkills($categoryId, $skillIds);

            DB::commit();
            return SkillMgmtResource::collection($skills);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
