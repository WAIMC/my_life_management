<?php

namespace App\Repositories\Master;

use App\Constants\CommonVal;
use App\Models\Master\TranslationMst;
use App\Interfaces\Master\TranslationMstInterface;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Database\Eloquent\Collection;

class TranslationMstRepository extends BaseRepository implements TranslationMstInterface
{
    public function __construct(TranslationMst $model)
    {
        parent::__construct($model);
    }

    /**
     * Get translation list with conditions
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->select('*');

        if (isset($payload['language_id'])) {
            $query->where('language_id', $payload['language_id']);
        }

        if (isset($payload['original_id'])) {
            $query->where('original_id', $payload['original_id']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('tad.updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('tad.updated_at', '<=', $toDate);
        }

        $query->orderBy('language_id')->orderBy('original_id');

        return $query->get();
    }

    /**
     * Store new translation
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        // Handle bulk insert
        $this->model->create($payload);
    }

    /**
     * Delete translation
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            return '(' . (int)$item['language_id'] . ', ' . (int)$item['original_id'] . ')';
        })->all();

        // Handle bulk delete
        $this->model
            ->whereRaw("(language_id, original_id) IN (" . implode(", ", $values) . ")")
            ->delete();
    }

    /**
     * Get translation id
     *
     * @param array $translationIds
     * @return Collection
     */
    public function getTranslationMstId(array $translationIds): Collection
    {
        return $this->model
            ->whereRaw("(language_id, original_id) IN (" . implode(", ", $translationIds) . ")")
            ->pluck('language_id', 'original_id');
    }
}
