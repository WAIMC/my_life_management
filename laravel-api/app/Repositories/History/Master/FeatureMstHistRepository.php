<?php

namespace App\Repositories\History\Master;

use App\Interfaces\History\Master\FeatureMstHistInterface;
use App\Models\History\Master\FeatureMstHist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FeatureMstHistRepository implements FeatureMstHistInterface
{
    /**
     * @var FeatureMstHist
     */
    protected $model;

    /**
     * FeatureMstHistRepository constructor.
     *
     * @param FeatureMstHist $model
     */
    public function __construct(FeatureMstHist $model)
    {
        $this->model = $model;
    }

    /**
     * Get all feature history records
     *
     * @param array $payload
     * @return Collection|LengthAwarePaginator
     */
    public function getAll(array $payload)
    {
        $query = $this->model->query();

        if (isset($payload['feature_mst_id'])) {
            $query->where('feature_mst_id', $payload['feature_mst_id']);
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['group_name'])) {
            $query->where('group_name', 'like', '%' . $payload['group_name'] . '%');
        }

        if (isset($payload['description'])) {
            $query->where('description', 'like', '%' . $payload['description'] . '%');
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

        if (isset($payload['created_from'])) {
            $query->where('created_at', '>=', $payload['created_from']);
        }

        if (isset($payload['created_to'])) {
            $query->where('created_at', '<=', $payload['created_to']);
        }

        // Order by
        $orderBy = isset($payload['order_by']) ? $payload['order_by'] : 'created_at';
        $order = isset($payload['order']) ? $payload['order'] : 'desc';
        $query->orderBy($orderBy, $order);

        // Pagination
        if (isset($payload['per_page'])) {
            return $query->paginate($payload['per_page']);
        }

        return $query->get();
    }

    /**
     * Get feature history record by ID
     *
     * @param int $id
     * @return FeatureMstHist|null
     */
    public function getById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Get history records by feature ID
     *
     * @param int $featureMstId
     * @param array $payload
     * @return Collection|LengthAwarePaginator
     */
    public function getByFeatureId(int $featureMstId, array $payload)
    {
        $query = $this->model->where('feature_mst_id', $featureMstId);

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        // Order by
        $orderBy = isset($payload['order_by']) ? $payload['order_by'] : 'created_at';
        $order = isset($payload['order']) ? $payload['order'] : 'desc';
        $query->orderBy($orderBy, $order);

        // Pagination
        if (isset($payload['per_page'])) {
            return $query->paginate($payload['per_page']);
        }

        return $query->get();
    }

    /**
     * Create new feature history record
     *
     * @param array $payload
     * @return FeatureMstHist
     */
    public function create(array $payload)
    {
        $featureMstHist = new $this->model;

        if (isset($payload['id'])) {
            $featureMstHist->id = $payload['id'];
        }
        
        if (isset($payload['feature_mst_id'])) {
            $featureMstHist->feature_mst_id = $payload['feature_mst_id'];
        }
        
        if (isset($payload['name'])) {
            $featureMstHist->name = $payload['name'];
        }
        
        if (isset($payload['group_name'])) {
            $featureMstHist->group_name = $payload['group_name'];
        }
        
        if (isset($payload['description'])) {
            $featureMstHist->description = $payload['description'];
        }
        
        if (isset($payload['status'])) {
            $featureMstHist->status = $payload['status'];
        }
        
        if (isset($payload['action'])) {
            $featureMstHist->action = $payload['action'];
        }
        
        if (isset($payload['author_id'])) {
            $featureMstHist->author_id = $payload['author_id'];
        }
        
        $featureMstHist->created_at = now()->format('Y-m-d H:i:s');
        
        $featureMstHist->save();
        
        return $featureMstHist;
    }

    /**
     * Update feature history record
     *
     * @param array $payload
     * @param int $id
     * @return FeatureMstHist|null
     */
    public function update(array $payload, int $id)
    {
        $featureMstHist = $this->model->find($id);
        
        if (!$featureMstHist) {
            return null;
        }
        
        if (isset($payload['feature_mst_id'])) {
            $featureMstHist->feature_mst_id = $payload['feature_mst_id'];
        }
        
        if (isset($payload['name'])) {
            $featureMstHist->name = $payload['name'];
        }
        
        if (isset($payload['group_name'])) {
            $featureMstHist->group_name = $payload['group_name'];
        }
        
        if (isset($payload['description'])) {
            $featureMstHist->description = $payload['description'];
        }
        
        if (isset($payload['status'])) {
            $featureMstHist->status = $payload['status'];
        }
        
        if (isset($payload['action'])) {
            $featureMstHist->action = $payload['action'];
        }
        
        if (isset($payload['author_id'])) {
            $featureMstHist->author_id = $payload['author_id'];
        }
        
        $featureMstHist->save();
        
        return $featureMstHist;
    }

    /**
     * Delete feature history record
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        $featureMstHist = $this->model->find($id);
        
        if (!$featureMstHist) {
            return false;
        }
        
        return $featureMstHist->delete();
    }
}