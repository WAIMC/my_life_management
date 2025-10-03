<?php

declare(strict_types=1);

namespace App\Repositories\History\Master;

use App\Enums\IsDelete;
use App\Interfaces\History\Master\OriginalTranslatorMstHistInterface;
use App\Models\History\Master\OriginalTranslatorMstHist;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class OriginalTranslatorMstHistRepository extends BaseRepository implements OriginalTranslatorMstHistInterface
{
    public function __construct(OriginalTranslatorMstHist $model)
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
                'original_translator_mst_id',
                '"table"',
                '"column"',
                'field_id',
                'action',
                'author_id',
            ]);

        if (isset($payload['original_translator_mst_id'])) {
            $query->where('original_translator_mst_id', $payload['original_translator_mst_id']);
        }

        if (isset($payload['"table"'])) {
            $query->where('"table"', 'like', '%' . $payload['"table"'] . '%');
        }

        if (isset($payload['"column"'])) {
            $query->where('"column"', 'like', '%' . $payload['"column"'] . '%');
        }

        if (isset($payload['field_id'])) {
            $query->where('field_id', $payload['field_id']);
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
        $data['original_translator_mst_id'] = $payload['original_translator_mst_id'] ?? null;
        $data['"table"'] = $payload['"table"'] ?? null;
        $data['"column"'] = $payload['"column"'] ?? null;
        $data['field_id'] = $payload['field_id'] ?? null;
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
        $record['original_translator_mst_id'] = $payload['original_translator_mst_id'] ?? null;
        $record['"table"'] = $payload['"table"'] ?? null;
        $record['"column"'] = $payload['"column"'] ?? null;
        $record['field_id'] = $payload['field_id'] ?? null;
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
