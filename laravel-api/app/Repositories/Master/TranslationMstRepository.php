<?php

declare(strict_types=1);

namespace App\Repositories\Master;

use App\Enums\IsDelete;
use App\Interfaces\Master\TranslationMstInterface;
use App\Models\Master\TranslationMst;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class TranslationMstRepository extends BaseRepository implements TranslationMstInterface
{
    public function __construct(TranslationMst $model)
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
                'language_id',
                'original_id',
                'value',
                'updated_at',
            ]);

        if (isset($payload['language_id'])) {
            $query->where('language_id', $payload['language_id']);
        }

        if (isset($payload['original_id'])) {
            $query->where('original_id', $payload['original_id']);
        }

        if (isset($payload['value'])) {
            $query->where('value', 'like', '%' . $payload['value'] . '%');
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
        $data['language_id'] = $payload['language_id'] ?? null;
        $data['original_id'] = $payload['original_id'] ?? null;
        $data['value'] = $payload['value'] ?? null;
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
        $record['language_id'] = $payload['language_id'] ?? null;
        $record['original_id'] = $payload['original_id'] ?? null;
        $record['value'] = $payload['value'] ?? null;
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
