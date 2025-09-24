<?php

namespace App\Repositories\Management;

use App\Constants\CommonVal;
use App\Interfaces\Management\SkillMgmtInterface;
use App\Models\Management\SkillMgmt;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SkillMgmtRepository extends BaseRepository implements SkillMgmtInterface
{
    public function __construct(SkillMgmt $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all skills with pagination and filtering
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
     * Create new skill
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['parent_id'] = $payload['parent_id'];
        $data['name'] = $payload['name'];
        $data['slug'] = $payload['slug'] ? Str::slug($payload['slug']) : Str::slug($payload['name']);
        $data['status'] = $payload['status'];
        $data['is_display'] = $payload['is_display'];
        $data['rank_order'] = $payload['rank_order'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update skill by ID
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['parent_id'] = $payload['parent_id'];
        $data['name'] = $payload['name'];
        $data['slug'] = $payload['slug'] ? Str::slug($payload['slug']) : Str::slug($payload['name']);
        $data['status'] = $payload['status'];
        $data['is_display'] = $payload['is_display'];
        $data['rank_order'] = $payload['rank_order'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete skill by ID
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
