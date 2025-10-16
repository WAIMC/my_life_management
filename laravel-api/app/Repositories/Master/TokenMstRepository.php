<?php

declare(strict_types=1);

namespace App\Repositories\Master;

use App\Enums\IsDelete;
use App\Interfaces\Master\TokenMstInterface;
use App\Models\Master\TokenMst;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class TokenMstRepository extends BaseRepository implements TokenMstInterface
{
    public function __construct(TokenMst $model)
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
                'token_hash',
                'account_id',
                'device_name',
                'ip_address',
                'expired_at',
                'updated_at',
            ]);

        if (isset($payload['token_hash'])) {
            $query->where('token_hash', 'like', '%' . $payload['token_hash'] . '%');
        }

        if (isset($payload['account_id'])) {
            $query->where('account_id', $payload['account_id']);
        }

        if (isset($payload['device_name'])) {
            $query->where('device_name', 'like', '%' . $payload['device_name'] . '%');
        }

        if (isset($payload['ip_address'])) {
            $query->where('ip_address', 'like', '%' . $payload['ip_address'] . '%');
        }

        if (isset($payload['expired_at'])) {
            $query->where('expired_at', $payload['expired_at']);
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
        $data['token_hash'] = $payload['token_hash'] ?? null;
        $data['account_id'] = $payload['account_id'] ?? null;
        $data['device_name'] = $payload['device_name'] ?? null;
        $data['ip_address'] = $payload['ip_address'] ?? null;
        $data['expired_at'] = $payload['expired_at'] ?? null;
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
        $record['token_hash'] = $payload['token_hash'] ?? null;
        $record['account_id'] = $payload['account_id'] ?? null;
        $record['device_name'] = $payload['device_name'] ?? null;
        $record['ip_address'] = $payload['ip_address'] ?? null;
        $record['expired_at'] = $payload['expired_at'] ?? null;
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
