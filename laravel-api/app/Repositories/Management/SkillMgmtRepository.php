<?php

namespace App\Repositories\Management;

use App\Interfaces\Management\SkillMgmtInterface;
use App\Models\Management\SkillMgmt;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class SkillMgmtRepository implements SkillMgmtInterface
{
    protected SkillMgmt $model;

    /**
     * SkillMgmtRepository constructor
     */
    public function __construct()
    {
        $this->model = new SkillMgmt();
    }

    /**
     * Get all skills with pagination and filtering
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters
        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['is_display'])) {
            $query->where('is_display', $payload['is_display']);
        }

        if (isset($payload['parent_id'])) {
            $query->where('parent_id', $payload['parent_id']);
        }

        // Apply sorting
        $sortField = $payload['sort_field'] ?? 'rank_order';
        $sortOrder = $payload['sort_order'] ?? 'asc';
        $query->orderBy($sortField, $sortOrder);

        // With parent relationship
        if (isset($payload['with_parent']) && $payload['with_parent']) {
            $query->with('parent');
        }

        // With children relationship
        if (isset($payload['with_children']) && $payload['with_children']) {
            $query->with('children');
        }

        // Paginate results
        $perPage = $payload['per_page'] ?? 15;
        
        return $query->paginate($perPage);
    }

    /**
     * Find skill by ID
     *
     * @param int $id
     * @return SkillMgmt|null
     */
    public function findById(int $id): ?SkillMgmt
    {
        return $this->model->with(['parent', 'children'])->find($id);
    }

    /**
     * Create new skill
     *
     * @param array $payload
     * @return SkillMgmt
     */
    public function create(array $payload): SkillMgmt
    {
        $skill = new SkillMgmt();

        if (isset($payload['parent_id'])) {
            $skill->parent_id = $payload['parent_id'];
        }
        
        if (isset($payload['name'])) {
            $skill->name = $payload['name'];
        }
        
        if (isset($payload['slug'])) {
            $skill->slug = $payload['slug'];
        } else {
            $skill->slug = Str::slug($payload['name']);
        }
        
        if (isset($payload['status'])) {
            $skill->status = $payload['status'];
        }
        
        if (isset($payload['is_display'])) {
            $skill->is_display = $payload['is_display'];
        }
        
        if (isset($payload['rank_order'])) {
            $skill->rank_order = $payload['rank_order'];
        }

        $skill->save();
        
        return $skill;
    }

    /**
     * Update skill by ID
     *
     * @param int $id
     * @param array $payload
     * @return SkillMgmt|null
     */
    public function update(int $id, array $payload): ?SkillMgmt
    {
        $skill = $this->findById($id);
        
        if (!$skill) {
            return null;
        }

        if (isset($payload['parent_id'])) {
            $skill->parent_id = $payload['parent_id'];
        }
        
        if (isset($payload['name'])) {
            $skill->name = $payload['name'];
        }
        
        if (isset($payload['slug'])) {
            $skill->slug = $payload['slug'];
        }
        
        if (isset($payload['status'])) {
            $skill->status = $payload['status'];
        }
        
        if (isset($payload['is_display'])) {
            $skill->is_display = $payload['is_display'];
        }
        
        if (isset($payload['rank_order'])) {
            $skill->rank_order = $payload['rank_order'];
        }

        $skill->save();
        
        return $skill;
    }

    /**
     * Delete skill by ID
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $skill = $this->findById($id);
        
        if (!$skill) {
            return false;
        }
        
        // Check if this skill has children
        if ($skill->children()->count() > 0) {
            // Set children's parent_id to null or to the parent of the skill being deleted
            $skill->children()->update(['parent_id' => $skill->parent_id]);
        }
        
        return $skill->delete();
    }
}
