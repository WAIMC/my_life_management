<?php

namespace App\Repositories\History\Master;

use App\Constants\CommonVal;
use App\Interfaces\History\Master\OriginalTranslatorMstHistInterface;
use App\Models\History\Master\OriginalTranslatorMstHist;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class OriginalTranslatorMstHistRepository extends BaseRepository implements OriginalTranslatorMstHistInterface
{
    public function __construct(OriginalTranslatorMstHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all history records.
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

        if (isset($payload['original_translator_mst_id'])) {
            $query->where('original_translator_mst_id', $payload['original_translator_mst_id']);
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

        $query->orderBy('id', 'desc');

        return $query->get();
    }

    /**
     * Create new history record.
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['original_translator_mst_id'] = $payload['original_translator_mst_id'];
        $data['table'] = $payload['table'];
        $data['column'] = $payload['column'];
        $data['field_id'] = $payload['field_id'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update history record.
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['original_translator_mst_id'] = $payload['original_translator_mst_id'];
        $data['table'] = $payload['table'];
        $data['column'] = $payload['column'];
        $data['field_id'] = $payload['field_id'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete history record.
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
