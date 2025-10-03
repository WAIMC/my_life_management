<?php

declare(strict_types=1);

namespace App\Repositories\History\Management;

use App\Enums\IsDelete;
use App\Interfaces\History\Management\SkillDescriptionMgmtHistInterface;
use App\Models\History\Management\SkillDescriptionMgmtHist;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class SkillDescriptionMgmtHistRepository extends BaseRepository implements SkillDescriptionMgmtHistInterface
{
    public function __construct(SkillDescriptionMgmtHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()
            ->select([
                'id',
                'skill_description_mgmt_id',
                'parent_id',
                'title',
                'summary',
                'article',
                'status',
                'is_display',
                'rank_order',
                'skill_id',
                'action',
                'author_id',
            ]);

        if (isset($payload['skill_description_mgmt_id'])) {
            $query->where('skill_description_mgmt_id', $payload['skill_description_mgmt_id']);
        }

        if (isset($payload['parent_id'])) {
            $query->where('parent_id', $payload['parent_id']);
        }

        if (isset($payload['title'])) {
            $query->where('title', 'like', '%' . $payload['title'] . '%');
        }

        if (isset($payload['summary'])) {
            $query->where('summary', 'like', '%' . $payload['summary'] . '%');
        }

        if (isset($payload['article'])) {
            $query->where('article', 'like', '%' . $payload['article'] . '%');
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['is_display'])) {
            $query->where('is_display', $payload['is_display']);
        }

        if (isset($payload['rank_order'])) {
            $query->where('rank_order', $payload['rank_order']);
        }

        if (isset($payload['skill_id'])) {
            $query->where('skill_id', $payload['skill_id']);
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

        $query->orderBy('id');

        return $query->get();
    }

    /**
     * Create new record
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data['skill_description_mgmt_id'] = $payload['skill_description_mgmt_id'] ?? null;
        $data['parent_id'] = $payload['parent_id'] ?? null;
        $data['title'] = $payload['title'] ?? null;
        $data['summary'] = $payload['summary'] ?? null;
        $data['article'] = $payload['article'] ?? null;
        $data['status'] = $payload['status'] ?? null;
        $data['is_display'] = $payload['is_display'] ?? null;
        $data['rank_order'] = $payload['rank_order'] ?? null;
        $data['skill_id'] = $payload['skill_id'] ?? null;
        $data['action'] = $payload['action'] ?? null;
        $data['author_id'] = $payload['author_id'] ?? null;
        $this->model->create($data);

        return $this->model->id;
    }


    /**
     * Update record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $record = $this->model->find($payload['id']);
        $record['skill_description_mgmt_id'] = $payload['skill_description_mgmt_id'] ?? null;
        $record['parent_id'] = $payload['parent_id'] ?? null;
        $record['title'] = $payload['title'] ?? null;
        $record['summary'] = $payload['summary'] ?? null;
        $record['article'] = $payload['article'] ?? null;
        $record['status'] = $payload['status'] ?? null;
        $record['is_display'] = $payload['is_display'] ?? null;
        $record['rank_order'] = $payload['rank_order'] ?? null;
        $record['skill_id'] = $payload['skill_id'] ?? null;
        $record['action'] = $payload['action'] ?? null;
        $record['author_id'] = $payload['author_id'] ?? null;
        $record->save();

        return $record->id;
    }

    /**
     * Delete record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->update(['is_delete' => IsDelete::TRUE->value]);
    }

}
