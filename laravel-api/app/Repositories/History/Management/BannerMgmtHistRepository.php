<?php

declare(strict_types=1);

namespace App\Repositories\History\Management;

use App\Enums\IsDelete;
use App\Interfaces\History\Management\BannerMgmtHistInterface;
use App\Models\History\Management\BannerMgmtHist;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class BannerMgmtHistRepository extends BaseRepository implements BannerMgmtHistInterface
{
    public function __construct(BannerMgmtHist $model)
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
                'banner_mgmt_id',
                'title',
                'slug',
                'description',
                'link',
                'image',
                'position',
                'status',
                'action',
                'author_id',
            ]);

        if (isset($payload['banner_mgmt_id'])) {
            $query->where('banner_mgmt_id', $payload['banner_mgmt_id']);
        }

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
        $data['banner_mgmt_id'] = $payload['banner_mgmt_id'] ?? null;
        $data['title'] = $payload['title'] ?? null;
        $data['slug'] = $payload['slug'] ?? null;
        $data['description'] = $payload['description'] ?? null;
        $data['link'] = $payload['link'] ?? null;
        $data['image'] = $payload['image'] ?? null;
        $data['position'] = $payload['position'] ?? null;
        $data['status'] = $payload['status'] ?? null;
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
        $record['banner_mgmt_id'] = $payload['banner_mgmt_id'] ?? null;
        $record['title'] = $payload['title'] ?? null;
        $record['slug'] = $payload['slug'] ?? null;
        $record['description'] = $payload['description'] ?? null;
        $record['link'] = $payload['link'] ?? null;
        $record['image'] = $payload['image'] ?? null;
        $record['position'] = $payload['position'] ?? null;
        $record['status'] = $payload['status'] ?? null;
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
