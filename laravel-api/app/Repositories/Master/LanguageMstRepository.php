<?php

namespace App\Repositories\Master;

use App\Constants\CommonVal;
use App\Interfaces\Master\LanguageMstInterface;
use App\Models\Master\LanguageMst;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class LanguageMstRepository extends BaseRepository implements LanguageMstInterface
{
    public function __construct(LanguageMst $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all languages
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

        if (isset($payload['abbreviation'])) {
            $query->where('abbreviation', 'like', '%' . $payload['abbreviation'] . '%');
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['is_active'])) {
            $query->where('is_active', $payload['is_active']);
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
     * Create new language
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload):int
    {
        $data = [];
        $data['abbreviation'] = $payload['abbreviation'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['is_active'] = $payload['is_active'] ?? null;
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update language
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload):int
    {
        $data = $this->model->findById($payload['id']);
        $data['abbreviation'] = $payload['abbreviation'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['is_active'] = $payload['is_active'] ?? null;
        $data->save();

        return $data->id;
    }

    /**
     * Delete language
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
