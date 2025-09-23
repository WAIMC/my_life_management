<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\OriginalTranslatorMstInterface;
use App\Models\Master\OriginalTranslatorMst;
use Illuminate\Support\Facades\DB;

class OriginalTranslatorMstRepository implements OriginalTranslatorMstInterface
{
    /**
     * @var OriginalTranslatorMst
     */
    protected $model;

    /**
     * OriginalTranslatorMstRepository constructor.
     *
     * @param OriginalTranslatorMst $model
     */
    public function __construct(OriginalTranslatorMst $model)
    {
        $this->model = $model;
    }

    /**
     * Get list of original translators
     *
     * @param array $payload
     * @return mixed
     */
    public function getList(array $payload)
    {
        $query = $this->model->query();

        if (isset($payload['id'])) {
            $query->where('id', $payload['id']);
        }

        if (isset($payload['table'])) {
            $query->where('table', 'like', '%' . $payload['table'] . '%');
        }

        if (isset($payload['column'])) {
            $query->where('column', 'like', '%' . $payload['column'] . '%');
        }

        if (isset($payload['field_id'])) {
            $query->where('field_id', $payload['field_id']);
        }

        if (isset($payload['created_at'])) {
            $query->whereDate('created_at', $payload['created_at']);
        }

        if (isset($payload['updated_at'])) {
            $query->whereDate('updated_at', $payload['updated_at']);
        }

        if (isset($payload['sort_by']) && isset($payload['sort_direction'])) {
            $query->orderBy($payload['sort_by'], $payload['sort_direction']);
        } else {
            $query->orderBy('id', 'desc');
        }

        $perPage = $payload['per_page'] ?? 10;
        
        return $query->paginate($perPage);
    }

    /**
     * Get original translator by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create original translator
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload)
    {
        $originalTranslator = new $this->model;
        
        $originalTranslator->id = $payload['id'] ?? $this->getNextId();
        $originalTranslator->table = $payload['table'];
        $originalTranslator->column = $payload['column'];
        $originalTranslator->field_id = $payload['field_id'];
        
        $originalTranslator->save();
        
        return $originalTranslator;
    }

    /**
     * Update original translator
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id)
    {
        $originalTranslator = $this->model->findOrFail($id);
        
        if (isset($payload['table'])) {
            $originalTranslator->table = $payload['table'];
        }
        
        if (isset($payload['column'])) {
            $originalTranslator->column = $payload['column'];
        }
        
        if (isset($payload['field_id'])) {
            $originalTranslator->field_id = $payload['field_id'];
        }
        
        $originalTranslator->save();
        
        return $originalTranslator;
    }

    /**
     * Delete original translator
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        $originalTranslator = $this->model->findOrFail($id);
        return $originalTranslator->delete();
    }

    /**
     * Get next ID for the model
     *
     * @return int
     */
    private function getNextId()
    {
        $statement = DB::select("SELECT nextval('original_translator_mst_seq')");
        return $statement[0]->nextval;
    }
}
