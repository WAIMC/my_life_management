<?php

namespace App\Repositories\History\Master;

use App\Interfaces\History\Master\LanguageMstHistInterface;
use App\Models\History\Master\LanguageMstHist;
use Illuminate\Support\Facades\DB;

class LanguageMstHistRepository implements LanguageMstHistInterface
{
    /**
     * @var LanguageMstHist
     */
    protected $model;

    /**
     * LanguageMstHistRepository constructor.
     *
     * @param LanguageMstHist $model
     */
    public function __construct(LanguageMstHist $model)
    {
        $this->model = $model;
    }

    /**
     * Get list of language history
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

        if (isset($payload['language_mst_id'])) {
            $query->where('language_mst_id', $payload['language_mst_id']);
        }

        if (isset($payload['abbreviation'])) {
            $query->where('abbreviation', 'like', '%' . $payload['abbreviation'] . '%');
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['is_active'])) {
            $query->where('is_active', $payload['is_active']);
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }

        if (isset($payload['created_at'])) {
            $query->whereDate('created_at', $payload['created_at']);
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
     * Get language history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create language history
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload)
    {
        $languageHist = new $this->model;
        
        $languageHist->id = $payload['id'] ?? $this->getNextId();
        $languageHist->language_mst_id = $payload['language_mst_id'];
        $languageHist->abbreviation = $payload['abbreviation'] ?? null;
        $languageHist->name = $payload['name'] ?? null;
        $languageHist->is_active = $payload['is_active'] ?? false;
        $languageHist->action = $payload['action'];
        $languageHist->author_id = $payload['author_id'];
        $languageHist->created_at = now();
        
        $languageHist->save();
        
        return $languageHist;
    }

    /**
     * Update language history
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id)
    {
        $languageHist = $this->model->findOrFail($id);
        
        if (isset($payload['language_mst_id'])) {
            $languageHist->language_mst_id = $payload['language_mst_id'];
        }
        
        if (isset($payload['abbreviation'])) {
            $languageHist->abbreviation = $payload['abbreviation'];
        }
        
        if (isset($payload['name'])) {
            $languageHist->name = $payload['name'];
        }
        
        if (isset($payload['is_active'])) {
            $languageHist->is_active = $payload['is_active'];
        }
        
        if (isset($payload['action'])) {
            $languageHist->action = $payload['action'];
        }
        
        if (isset($payload['author_id'])) {
            $languageHist->author_id = $payload['author_id'];
        }
        
        $languageHist->save();
        
        return $languageHist;
    }

    /**
     * Delete language history
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        $languageHist = $this->model->findOrFail($id);
        return $languageHist->delete();
    }

    /**
     * Get next ID for the model
     *
     * @return int
     */
    private function getNextId()
    {
        $statement = DB::select("SELECT nextval('language_mst_hist_seq')");
        return $statement[0]->nextval;
    }
}
