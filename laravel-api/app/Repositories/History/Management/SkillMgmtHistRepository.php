<?php

namespace App\Repositories\History\Management;

use App\Constants\CommonVal;
use App\Interfaces\History\Management\SkillMgmtHistInterface;
use App\Models\History\Management\SkillMgmtHist;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Database\Eloquent\Collection;

class SkillMgmtHistRepository extends BaseRepository implements SkillMgmtHistInterface
{
    public function __construct(SkillMgmtHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all skill history records with filtering and pagination
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query();

        if (isset($payload['id'])) {
            $query->whereIn('id', $payload['id']);
        }

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
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('updated_at', '<=', $toDate);
        }

        $query->orderBy('id', 'desc');

        return $query->get();
    }

    /**
     * Create a new skill history record
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['skill_mgmt_id'] = $payload['skill_mgmt_id'];
        $data['parent_id'] = $payload['parent_id'];
        $data['name'] = $payload['name'];
        $data['slug'] = $payload['slug'];
        $data['status'] = $payload['status'];
        $data['is_display'] = $payload['is_display'];
        $data['rank_order'] = $payload['rank_order'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update skill history record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['skill_mgmt_id'] = $payload['skill_mgmt_id'];
        $data['parent_id'] = $payload['parent_id'];
        $data['name'] = $payload['name'];
        $data['slug'] = $payload['slug'];
        $data['status'] = $payload['status'];
        $data['is_display'] = $payload['is_display'];
        $data['rank_order'] = $payload['rank_order'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete skill history record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
