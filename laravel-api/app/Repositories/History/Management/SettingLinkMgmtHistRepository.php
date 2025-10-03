<?php

declare(strict_types=1);

namespace App\Repositories\History\Management;

use App\Enums\IsDelete;
use App\Interfaces\History\Management\SettingLinkMgmtHistInterface;
use App\Models\History\Management\SettingLinkMgmtHist;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class SettingLinkMgmtHistRepository extends BaseRepository implements SettingLinkMgmtHistInterface
{
    public function __construct(SettingLinkMgmtHist $model)
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
                'setting_link_id',
                'key',
                'value',
                'action',
                'author_id',
            ]);

        if (isset($payload['setting_link_id'])) {
            $query->where('setting_link_id', $payload['setting_link_id']);
        }

        if (isset($payload['key'])) {
            $query->where('key', 'like', '%' . $payload['key'] . '%');
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
        $data['setting_link_id'] = $payload['setting_link_id'] ?? null;
        $data['key'] = $payload['key'] ?? null;
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
        $record['setting_link_id'] = $payload['setting_link_id'] ?? null;
        $record['key'] = $payload['key'] ?? null;
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
