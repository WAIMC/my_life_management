<?php

namespace App\Repositories\History\Management;

use App\Interfaces\History\Management\SkillMgmtHistInterface;
use App\Models\History\Management\SkillMgmtHist;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SkillMgmtHistRepository implements SkillMgmtHistInterface
{
    protected SkillMgmtHist $model;

    /**
     * SkillMgmtHistRepository constructor
     *
     * @param SkillMgmtHist $model
     */
    public function __construct(SkillMgmtHist $model)
    {
        $this->model = $model;
    }

    /**
     * Get all skill history records with filtering and pagination
     *
     * @param array $payload
     * @return LengthAwarePaginator|Collection
     */
    public function getAll(array $payload = []): LengthAwarePaginator|Collection
    {
        $query = $this->model->query();

        // Apply filters
        if (isset($payload['skill_mgmt_id'])) {
            $query->where('skill_mgmt_id', $payload['skill_mgmt_id']);
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }

        if (isset($payload['from_date'])) {
            $query->whereDate('created_at', '>=', $payload['from_date']);
        }

        if (isset($payload['to_date'])) {
            $query->whereDate('created_at', '<=', $payload['to_date']);
        }

        // Apply sorting
        $sortBy = $payload['sort_by'] ?? 'created_at';
        $sortOrder = $payload['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Apply pagination or get all
        if (isset($payload['per_page'])) {
            return $query->paginate($payload['per_page']);
        }

        return $query->get();
    }

    /**
     * Find skill history by ID
     *
     * @param int $id
     * @return SkillMgmtHist
     * @throws Exception
     */
    public function findById(int $id): SkillMgmtHist
    {
        $skillHistory = $this->model->find($id);

        if (!$skillHistory) {
            throw new Exception("Skill history with ID {$id} not found");
        }

        return $skillHistory;
    }

    /**
     * Find skill history records by skill ID
     *
     * @param int $skillId
     * @return Collection
     */
    public function findBySkillId(int $skillId): Collection
    {
        return $this->model
            ->where('skill_mgmt_id', $skillId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create a new skill history record
     *
     * @param array $payload
     * @return SkillMgmtHist
     */
    public function create(array $payload): SkillMgmtHist
    {
        $skillHistory = new $this->model;

        if (isset($payload['skill_mgmt_id'])) {
            $skillHistory->skill_mgmt_id = $payload['skill_mgmt_id'];
        }

        if (isset($payload['parent_id'])) {
            $skillHistory->parent_id = $payload['parent_id'];
        }

        if (isset($payload['name'])) {
            $skillHistory->name = $payload['name'];
        }

        if (isset($payload['slug'])) {
            $skillHistory->slug = $payload['slug'];
        }

        if (isset($payload['status'])) {
            $skillHistory->status = $payload['status'];
        }

        if (isset($payload['is_display'])) {
            $skillHistory->is_display = $payload['is_display'];
        }

        if (isset($payload['rank_order'])) {
            $skillHistory->rank_order = $payload['rank_order'];
        }

        if (isset($payload['action'])) {
            $skillHistory->action = $payload['action'];
        }

        if (isset($payload['author_id'])) {
            $skillHistory->author_id = $payload['author_id'];
        }

        if (isset($payload['created_at'])) {
            $skillHistory->created_at = $payload['created_at'];
        }

        $skillHistory->save();

        return $skillHistory;
    }
}
