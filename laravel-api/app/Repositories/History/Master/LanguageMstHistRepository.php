<?php

declare(strict_types=1);

namespace App\Repositories\History\Master;

use App\Enums\IsDelete;
use App\Interfaces\History\Master\LanguageMstHistInterface;
use App\Models\History\Master\LanguageMstHist;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;


class LanguageMstHistRepository extends BaseRepository implements LanguageMstHistInterface
{
    public function __construct(LanguageMstHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list with pagination
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function list(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->select(['id','language_mst_id','code','name','status','action','author_id'])
            ->with(['languageMst:id,code,name', 'author:id,username']);
        $this->applyFilters($query, $payload, ['language_mst_id','status','action','author_id'], ['code','name']);
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
