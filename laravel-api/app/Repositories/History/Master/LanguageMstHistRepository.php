<?php

namespace App\Repositories\History\Master;

use App\Constants\CommonVal;
use App\Interfaces\History\Master\LanguageMstHistInterface;
use App\Models\History\Master\LanguageMstHist;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class LanguageMstHistRepository extends BaseRepository implements LanguageMstHistInterface
{
    public function __construct(LanguageMstHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list of language history
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

        if (isset($payload['language_mst_id'])) {
            $query->where('language_mst_id', $payload['language_mst_id']);
        }

        if (isset($payload['abbreviation'])) {
            $query->where('abbreviation', 'like', '%' . $payload['abbreviation'] . '%');
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['is_active'])) {
            $query->where('is_active', $payload['is_active']);
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
     * Create language history
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['language_mst_id'] = $payload['language_mst_id'];
        $data['abbreviation'] = $payload['abbreviation'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['is_active'] = $payload['is_active'] ?? false;
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update language history
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['language_mst_id'] = $payload['language_mst_id'];
        $data['abbreviation'] = $payload['abbreviation'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['is_active'] = $payload['is_active'] ?? false;
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete language history record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
