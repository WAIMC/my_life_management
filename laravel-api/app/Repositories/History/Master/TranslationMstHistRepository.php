<?php

declare(strict_types=1);

namespace App\Repositories\History\Master;

use App\Enums\IsDelete;
use App\Interfaces\History\Master\TranslationMstHistInterface;
use App\Models\History\Master\TranslationMstHist;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class TranslationMstHistRepository extends BaseRepository implements TranslationMstHistInterface
{
    public function __construct(TranslationMstHist $model)
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
                'translation_mst_id',
                'language_id',
                'original_id',
                'value',
                'action',
                'author_id',
            ]);

        if (isset($payload['translation_mst_id'])) {
            $query->where('translation_mst_id', $payload['translation_mst_id']);
        }

        if (isset($payload['language_id'])) {
            $query->where('language_id', $payload['language_id']);
        }

        if (isset($payload['original_id'])) {
            $query->where('original_id', $payload['original_id']);
        }

        if (isset($payload['value'])) {
            $query->where('value', 'like', '%' . $payload['value'] . '%');
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
        $data['translation_mst_id'] = $payload['translation_mst_id'] ?? null;
        $data['language_id'] = $payload['language_id'] ?? null;
        $data['original_id'] = $payload['original_id'] ?? null;
        $data['value'] = $payload['value'] ?? null;
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
        $record['translation_mst_id'] = $payload['translation_mst_id'] ?? null;
        $record['language_id'] = $payload['language_id'] ?? null;
        $record['original_id'] = $payload['original_id'] ?? null;
        $record['value'] = $payload['value'] ?? null;
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
