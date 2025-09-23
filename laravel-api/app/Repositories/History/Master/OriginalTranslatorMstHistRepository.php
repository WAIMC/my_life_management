<?php

namespace App\Repositories\History\Master;

use App\Interfaces\History\Master\OriginalTranslatorMstHistInterface;
use App\Models\History\Master\OriginalTranslatorMstHist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OriginalTranslatorMstHistRepository implements OriginalTranslatorMstHistInterface
{
    /**
     * @var OriginalTranslatorMstHist
     */
    protected OriginalTranslatorMstHist $model;

    /**
     * OriginalTranslatorMstHistRepository constructor.
     *
     * @param OriginalTranslatorMstHist $model
     */
    public function __construct(OriginalTranslatorMstHist $model)
    {
        $this->model = $model;
    }

    /**
     * Get all history records.
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters if provided
        if (isset($payload['original_translator_mst_id'])) {
            $query->where('original_translator_mst_id', $payload['original_translator_mst_id']);
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }

        // Default sorting by created_at in descending order
        $sortBy = $payload['sort_by'] ?? 'created_at';
        $sortOrder = $payload['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Get history record by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create new history record.
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }

    /**
     * Update history record.
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed
    {
        $record = $this->getById($id);

        if (isset($payload['original_translator_mst_id'])) {
            $record->original_translator_mst_id = $payload['original_translator_mst_id'];
        }

        if (isset($payload['table'])) {
            $record->table = $payload['table'];
        }

        if (isset($payload['column'])) {
            $record->column = $payload['column'];
        }

        if (isset($payload['field_id'])) {
            $record->field_id = $payload['field_id'];
        }

        if (isset($payload['action'])) {
            $record->action = $payload['action'];
        }

        if (isset($payload['author_id'])) {
            $record->author_id = $payload['author_id'];
        }

        $record->save();
        return $record;
    }

    /**
     * Delete history record.
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        $record = $this->getById($id);
        return $record->delete();
    }
}
