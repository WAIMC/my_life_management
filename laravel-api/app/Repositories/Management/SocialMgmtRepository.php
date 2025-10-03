<?php

declare(strict_types=1);

namespace App\Repositories\Management;

use App\Enums\IsDelete;
use App\Interfaces\Management\SocialMgmtInterface;
use App\Models\Management\SocialMgmt;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class SocialMgmtRepository extends BaseRepository implements SocialMgmtInterface
{
    public function __construct(SocialMgmt $model)
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
                'name',
                'slug',
                'link',
                'image',
                'status',
                'is_display',
                'rank_order',
                'updated_at',
            ]);

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['slug'])) {
            $query->where('slug', 'like', '%' . $payload['slug'] . '%');
        }

        if (isset($payload['link'])) {
            $query->where('link', $payload['link']);
        }

        if (isset($payload['image'])) {
            $query->where('image', $payload['image']);
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['is_display'])) {
            $query->where('is_display', $payload['is_display']);
        }

        if (isset($payload['rank_order'])) {
            $query->where('rank_order', $payload['rank_order']);
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
        $data['name'] = $payload['name'] ?? null;
        $data['slug'] = $payload['slug'] ?? null;
        $data['link'] = $payload['link'] ?? null;
        $data['image'] = $payload['image'] ?? null;
        $data['status'] = $payload['status'] ?? null;
        $data['is_display'] = $payload['is_display'] ?? null;
        $data['rank_order'] = $payload['rank_order'] ?? null;
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
        $record['name'] = $payload['name'] ?? null;
        $record['slug'] = $payload['slug'] ?? null;
        $record['link'] = $payload['link'] ?? null;
        $record['image'] = $payload['image'] ?? null;
        $record['status'] = $payload['status'] ?? null;
        $record['is_display'] = $payload['is_display'] ?? null;
        $record['rank_order'] = $payload['rank_order'] ?? null;
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
