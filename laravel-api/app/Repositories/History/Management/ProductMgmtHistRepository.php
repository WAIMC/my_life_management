<?php

namespace App\Repositories\History\Management;

use App\Constants\CommonVal;
use App\Interfaces\History\Management\ProductMgmtHistInterface;
use App\Models\History\Management\ProductMgmtHist;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class ProductMgmtHistRepository extends BaseRepository implements ProductMgmtHistInterface
{
    public function __construct(ProductMgmtHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all product history records with optional filtering
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

        if (isset($payload['product_mgmt_id'])) {
            $query->where('product_mgmt_id', $payload['product_mgmt_id']);
        }

        if (isset($payload['category_id'])) {
            $query->where('category_id', $payload['category_id']);
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', "%{$payload['name']}%");
        }

        if (isset($payload['code'])) {
            $query->where('code', 'like', "%{$payload['code']}%");
        }

        if (isset($payload['description'])) {
            $query->where('description', 'like', "%{$payload['description']}%");
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
     * Create new product history record
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['product_mgmt_id'] = $payload['product_mgmt_id'];
        $data['category_id'] = $payload['category_id'];
        $data['code'] = $payload['code'];
        $data['name'] = $payload['name'];
        $data['slug'] = $payload['slug'];
        $data['description'] = $payload['description'];
        $data['status'] = $payload['status'];
        $data['is_display'] = $payload['is_display'];
        $data['rank_order'] = $payload['rank_order'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update product history record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['product_mgmt_id'] = $payload['product_mgmt_id'];
        $data['category_id'] = $payload['category_id'];
        $data['code'] = $payload['code'];
        $data['name'] = $payload['name'];
        $data['slug'] = $payload['slug'];
        $data['description'] = $payload['description'];
        $data['status'] = $payload['status'];
        $data['is_display'] = $payload['is_display'];
        $data['rank_order'] = $payload['rank_order'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete product history record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
