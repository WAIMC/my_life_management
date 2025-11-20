<?php

declare(strict_types=1);

namespace App\Repositories\History\Master;

use App\Enums\IsDelete;
use App\Interfaces\History\Master\OriginalTranslatorMstHistInterface;
use App\Models\History\Master\OriginalTranslatorMstHist;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;


class OriginalTranslatorMstHistRepository extends BaseRepository implements OriginalTranslatorMstHistInterface
{
    public function __construct(OriginalTranslatorMstHist $model)
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
            ->select(['id','original_translator_mst_id','name','description','status','action','author_id'])
            ->with(['originalTranslatorMst:id,name', 'author:id,username']);
        $this->applyFilters($query, $payload, ['original_translator_mst_id','status','action','author_id'], ['name','description']);
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
