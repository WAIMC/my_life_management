<?php

namespace App\Repositories\Master;

use App\Constants\CommonVal;
use App\Interfaces\Master\OriginalTranslatorMstInterface;
use App\Models\Master\OriginalTranslatorMst;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class OriginalTranslatorMstRepository extends BaseRepository implements OriginalTranslatorMstInterface
{
    public function __construct(OriginalTranslatorMst $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list of original translators
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

        if (isset($payload['table'])) {
            $query->where('table', 'like', '%' . $payload['table'] . '%');
        }

        if (isset($payload['column'])) {
            $query->where('column', 'like', '%' . $payload['column'] . '%');
        }

        if (isset($payload['field_id'])) {
            $query->where('field_id', $payload['field_id']);
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
     * Create original translator
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['table'] = $payload['table'];
        $data['column'] = $payload['column'];
        $data['field_id'] = $payload['field_id'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update original translator
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['table'] = $payload['table'];
        $data['column'] = $payload['column'];
        $data['field_id'] = $payload['field_id'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete original translator
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
