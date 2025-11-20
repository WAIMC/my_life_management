<?php

declare(strict_types=1);

namespace App\Repositories\Master;

use App\Enums\IsDelete;
use App\Interfaces\Master\TranslationLanguageMstInterface;
use App\Models\Master\TranslationLanguageMst;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class TranslationLanguageMstRepository extends BaseRepository implements TranslationLanguageMstInterface
{
    public function __construct(TranslationLanguageMst $model)
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
            ->select([
                'translation_mst_id',
                'language_mst_id',
                'updated_at',
            ])
            ->with(['translationMst:id,key,value', 'languageMst:id,code,name']); // Eager load

        // Apply filters
        $this->applyFilters($query, $payload, [
            'translation_mst_id',
            'language_mst_id',
        ]);

        // Apply date range
        $this->applyDateRange($query, $payload);

        // Apply sorting
        $this->applySorting($query, $payload, 'translation_mst_id');

        // Pagination
        $perPage = $payload['per_page'] ?? 15;
        $page = $payload['page'] ?? 1;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Create new record
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $this->model->create($payload);
    }

    /**
     * Delete record
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            return '(' . (int)$item['translation_mst_id'] . ', ' . (int)$item['language_mst_id'] . ')';
        })->all();

        $this->model
            ->whereRaw("(translation_mst_id, language_mst_id) IN (" . implode(", ", $values) . ")")
            ->delete();
    }

    /**
     * Get ids
     *
     * @param array $tuples
     * @return Collection
     */
    public function getTranslationLanguageMstId(array $tuples): Collection
    {
        $values = collect($tuples)->map(function ($item) {
            return '(' . (int)$item['translation_mst_id'] . ', ' . (int)$item['language_mst_id'] . ')';
        })->all();

        return $this->model
            ->whereRaw("(translation_mst_id, language_mst_id) IN (" . implode(", ", $values) . ")")
            ->pluck('translation_mst_id', 'language_mst_id');
    }
}
