<?php

namespace App\Repositories\History\Management;

use App\Constants\CommonVal;
use App\Enums\IsDelete;
use App\Interfaces\History\Management\BannerMgmtHistInterface;
use App\Models\History\Management\BannerMgmtHist;
use App\Repositories\BaseRepository;
use DateTime;
use Mavinoo\Batch\Batch;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class BannerMgmtHistRepository extends BaseRepository implements BannerMgmtHistInterface
{
    public function __construct(BannerMgmtHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all banner history records with filtering
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

        if (isset($payload['banner_mgmt_id'])) {
            $query->where('banner_mgmt_id', $payload['banner_mgmt_id']);
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
     * Create new banner history records (batch)
     *
     * @param array $payloads
     * @return void
     */
    public function executeStore(array $payloads): void
    {
        $data = [];
        foreach ($payloads as $payload) {
            $data[] = [
                'banner_mgmt_id' => $payload['banner_mgmt_id'],
                'title' => $payload['title'],
                'slug' => $payload['slug'],
                'description' => $payload['description'],
                'link' => $payload['link'],
                'image' => $payload['image'],
                'position' => $payload['position'],
                'status' => $payload['status'],
                'action' => $payload['action'],
                'author_id' => $payload['author_id'],
            ];
        }

        $this->model->create($data);
    }

    /**
     * Update banner history records (batch)
     *
     * @param array $payloads
     * @return void
     */
    public function executeUpdate(array $payloads): void
    {
        $payloadIds = array_column($payloads, 'id');
        $existIds = $this->model->whereIn('id', $payloadIds)->pluck('id')->toArray();
        $diff = array_diff($payloadIds, $existIds);
        if (!empty($diff)) {
            throw new ModelNotFoundException(
                'Some records not found for update. Missing IDs: ' . implode(',', $diff)
            );
        }

        foreach ($payloads as $key => $payload) {
            $payloads[$key] = [
                'banner_mgmt_id' => $payload['banner_mgmt_id'],
                'title' => $payload['title'],
                'slug' => $payload['slug'],
                'description' => $payload['description'],
                'link' => $payload['link'],
                'image' => $payload['image'],
                'position' => $payload['position'],
                'status' => $payload['status'],
                'action' => $payload['action'],
                'author_id' => $payload['author_id'],
            ];
        }

        $batch = app(Batch::class);
        $batch->update(new $this->model, $payloads, 'id');
    }

    /**
     * Delete banner history records (batch)
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->update('is_deleted', IsDelete::TRUE->value); // Soft delete by setting is_deleted flag
    }
}
