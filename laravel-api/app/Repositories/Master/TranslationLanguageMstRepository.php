<?php

declare(strict_types=1);

namespace App\Repositories\Master;

use App\Enums\IsDelete;
use App\Interfaces\Master\TranslationLanguageMstInterface;
use App\Models\Master\TranslationLanguageMst;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class TranslationLanguageMstRepository extends BaseRepository implements TranslationLanguageMstInterface
{
    public function __construct(TranslationLanguageMst $model)
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
                'translation_mst_id',
                'language_mst_id',
                'updated_at',
            ]);

        if (isset($payload['translation_mst_id'])) {
            $query->where('translation_mst_id', $payload['translation_mst_id']);
        }

        if (isset($payload['language_mst_id'])) {
            $query->where('language_mst_id', $payload['language_mst_id']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('updated_at', '<=', $toDate);
        }

        $query->orderBy('translation_mst_id');

        return $query->get();
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
