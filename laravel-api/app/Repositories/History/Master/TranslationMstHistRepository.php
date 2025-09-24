<?php

namespace App\Repositories\History\Master;

use App\Constants\CommonVal;
use App\Interfaces\History\Master\TranslationMstHistInterface;
use App\Models\History\Master\TranslationMstHist;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class TranslationMstHistRepository extends BaseRepository implements TranslationMstHistInterface
{
    public function __construct(TranslationMstHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list of translation history
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query();

        if (isset($payload['id'])) {
            $query->where('id', $payload['id']);
        }

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

        $query->orderBy('id', 'desc');

        return $query->get();
    }

    /**
     * Create translation history
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['translation_mst_id'] = $payload['translation_mst_id'];
        $data['language_id'] = $payload['language_id'];
        $data['original_id'] = $payload['original_id'];
        $data['value'] = $payload['value'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update translation history record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['translation_mst_id'] = $payload['translation_mst_id'];
        $data['language_id'] = $payload['language_id'];
        $data['original_id'] = $payload['original_id'];
        $data['value'] = $payload['value'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete translation history record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
