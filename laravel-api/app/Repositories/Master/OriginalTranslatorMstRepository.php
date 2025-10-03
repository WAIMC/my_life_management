<?php

declare(strict_types=1);

namespace App\Repositories\Master;

use App\Enums\IsDelete;
use App\Interfaces\Master\OriginalTranslatorMstInterface;
use App\Models\Master\OriginalTranslatorMst;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class OriginalTranslatorMstRepository extends BaseRepository implements OriginalTranslatorMstInterface
{
    public function __construct(OriginalTranslatorMst $model)
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
                '"table"',
                '"column"',
                'field_id',
                'updated_at',
            ]);

        if (isset($payload['"table"'])) {
            $query->where('"table"', 'like', '%' . $payload['"table"'] . '%');
        }

        if (isset($payload['"column"'])) {
            $query->where('"column"', 'like', '%' . $payload['"column"'] . '%');
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
        $data['"table"'] = $payload['"table"'] ?? null;
        $data['"column"'] = $payload['"column"'] ?? null;
        $data['field_id'] = $payload['field_id'] ?? null;
        $data['is_delete'] = $payload['is_delete'] ?? null;
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
        $record['"table"'] = $payload['"table"'] ?? null;
        $record['"column"'] = $payload['"column"'] ?? null;
        $record['field_id'] = $payload['field_id'] ?? null;
        $record['is_delete'] = $payload['is_delete'] ?? null;
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
