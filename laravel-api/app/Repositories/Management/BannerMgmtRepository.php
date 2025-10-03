<?php

declare(strict_types=1);

namespace App\Repositories\Management;

use App\Enums\IsDelete;
use App\Interfaces\Management\BannerMgmtInterface;
use App\Models\Management\BannerMgmt;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class BannerMgmtRepository extends BaseRepository implements BannerMgmtInterface
{
    public function __construct(BannerMgmt $model)
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
                'title',
                'slug',
                'description',
                'link',
                'image',
                'position',
                'status',
                'updated_at',
            ]);

        if (isset($payload['title'])) {
            $query->where('title', 'like', '%' . $payload['title'] . '%');
        }

        if (isset($payload['slug'])) {
            $query->where('slug', 'like', '%' . $payload['slug'] . '%');
        }

        if (isset($payload['description'])) {
            $query->where('description', 'like', '%' . $payload['description'] . '%');
        }

        if (isset($payload['link'])) {
            $query->where('link', $payload['link']);
        }

        if (isset($payload['image'])) {
            $query->where('image', $payload['image']);
        }

        if (isset($payload['position'])) {
            $query->where('position', 'like', '%' . $payload['position'] . '%');
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
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
        $data['title'] = $payload['title'] ?? null;
        $data['slug'] = $payload['slug'] ?? null;
        $data['description'] = $payload['description'] ?? null;
        $data['link'] = $payload['link'] ?? null;
        $data['image'] = $payload['image'] ?? null;
        $data['position'] = $payload['position'] ?? null;
        $data['status'] = $payload['status'] ?? null;
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
        $record['title'] = $payload['title'] ?? null;
        $record['slug'] = $payload['slug'] ?? null;
        $record['description'] = $payload['description'] ?? null;
        $record['link'] = $payload['link'] ?? null;
        $record['image'] = $payload['image'] ?? null;
        $record['position'] = $payload['position'] ?? null;
        $record['status'] = $payload['status'] ?? null;
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
