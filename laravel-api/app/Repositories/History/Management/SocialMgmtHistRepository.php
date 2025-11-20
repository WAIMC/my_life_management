<?php

declare(strict_types=1);

namespace App\Repositories\History\Management;

use App\Enums\IsDelete;
use App\Interfaces\History\Management\SocialMgmtHistInterface;
use App\Models\History\Management\SocialMgmtHist;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;


class SocialMgmtHistRepository extends BaseRepository implements SocialMgmtHistInterface
{
    public function __construct(SocialMgmtHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->select([
                'id','social_mgmt_id','name','slug','link','image','status','is_display','rank_order','action','author_id',
            ])
            ->with(['socialMgmt:id,name', 'author:id,username']);
        $this->applyFilters($query, $payload, ['social_mgmt_id','link','image','status','is_display','rank_order','action','author_id'], ['name','slug']);
        $this->applyDateRange($query, $payload);
        $this->applySorting($query, $payload);
        return $query->paginate($payload['per_page'] ?? 15, ['*'], 'page', $payload['page'] ?? 1);
    }

    /**
     * Create new record
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $model = $this->model->fill(Arr::only($payload, $this->model->getFillable()));
        $model->save();
        return $model->id;
    }
        $data['slug'] = $payload['slug'] ?? null;
        $data['link'] = $payload['link'] ?? null;
        $data['image'] = $payload['image'] ?? null;
        $data['status'] = $payload['status'] ?? null;
        $data['is_display'] = $payload['is_display'] ?? null;
        $data['rank_order'] = $payload['rank_order'] ?? null;
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
        $model = $this->model->findOrFail($payload['id']);
        $model->fill(Arr::only($payload, $this->model->getFillable()));
        $model->save();
        return $model->id;
    }
        $record['social_mgmt_id'] = $payload['social_mgmt_id'] ?? null;
        $record['name'] = $payload['name'] ?? null;
        $record['slug'] = $payload['slug'] ?? null;
        $record['link'] = $payload['link'] ?? null;
        $record['image'] = $payload['image'] ?? null;
        $record['status'] = $payload['status'] ?? null;
        $record['is_display'] = $payload['is_display'] ?? null;
        $record['rank_order'] = $payload['rank_order'] ?? null;
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
