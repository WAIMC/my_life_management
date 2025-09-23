<?php

namespace App\Repositories\Management;

use App\Interfaces\Management\CategorySkillMgmtInterface;
use App\Models\Management\CategorySkillMgmt;
use App\Models\Management\CategoryMgmt;
use App\Models\Management\SkillMgmt;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategorySkillMgmtRepository implements CategorySkillMgmtInterface
{
    protected CategorySkillMgmt $model;

    /**
     * CategorySkillMgmtRepository constructor
     */
    public function __construct()
    {
        $this->model = new CategorySkillMgmt();
    }

    /**
     * Get all category-skill relationships
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters
        if (isset($payload['category_id'])) {
            $query->where('category_id', $payload['category_id']);
        }

        if (isset($payload['skill_id'])) {
            $query->where('skill_id', $payload['skill_id']);
        }

        // Apply pagination
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Get skills by category ID
     *
     * @param int $categoryId
     * @return mixed
     */
    public function getSkillsByCategoryId(int $categoryId): mixed
    {
        $category = CategoryMgmt::find($categoryId);

        if (!$category) {
            return collect();
        }

        $skillIds = $this->model->where('category_id', $categoryId)
            ->pluck('skill_id')
            ->toArray();

        return SkillMgmt::whereIn('id', $skillIds)->get();
    }

    /**
     * Get categories by skill ID
     *
     * @param int $skillId
     * @return mixed
     */
    public function getCategoriesBySkillId(int $skillId): mixed
    {
        $skill = SkillMgmt::find($skillId);

        if (!$skill) {
            return collect();
        }

        $categoryIds = $this->model->where('skill_id', $skillId)
            ->pluck('category_id')
            ->toArray();

        return CategoryMgmt::whereIn('id', $categoryIds)->get();
    }

    /**
     * Attach a skill to a category
     *
     * @param int $categoryId
     * @param int $skillId
     * @return mixed
     */
    public function attachSkill(int $categoryId, int $skillId): mixed
    {
        // Check if already exists
        if ($this->exists($categoryId, $skillId)) {
            return null;
        }

        $relation = new $this->model;
        $relation->category_id = $categoryId;
        $relation->skill_id = $skillId;
        $relation->created_at = now()->format('Y-m-d H:i:s');
        $relation->updated_at = now()->format('Y-m-d H:i:s');
        $relation->save();

        return $relation;
    }

    /**
     * Detach a skill from a category
     *
     * @param int $categoryId
     * @param int $skillId
     * @return mixed
     */
    public function detachSkill(int $categoryId, int $skillId): mixed
    {
        return $this->model->where('category_id', $categoryId)
            ->where('skill_id', $skillId)
            ->delete();
    }

    /**
     * Sync skills for a category
     *
     * @param int $categoryId
     * @param array $skillIds
     * @return mixed
     */
    public function syncSkills(int $categoryId, array $skillIds): mixed
    {
        // Get current skill IDs for this category
        $currentSkillIds = $this->model->where('category_id', $categoryId)
            ->pluck('skill_id')
            ->toArray();

        // Determine which skills to add and which to remove
        $skillsToAdd = array_diff($skillIds, $currentSkillIds);
        $skillsToRemove = array_diff($currentSkillIds, $skillIds);

        // Remove skills not in the new list
        if (!empty($skillsToRemove)) {
            $this->model->where('category_id', $categoryId)
                ->whereIn('skill_id', $skillsToRemove)
                ->delete();
        }

        // Add new skills
        foreach ($skillsToAdd as $skillId) {
            $this->attachSkill($categoryId, $skillId);
        }

        return $this->getSkillsByCategoryId($categoryId);
    }

    /**
     * Check if a relationship exists
     *
     * @param int $categoryId
     * @param int $skillId
     * @return bool
     */
    public function exists(int $categoryId, int $skillId): bool
    {
        return $this->model->where('category_id', $categoryId)
            ->where('skill_id', $skillId)
            ->exists();
    }
}
