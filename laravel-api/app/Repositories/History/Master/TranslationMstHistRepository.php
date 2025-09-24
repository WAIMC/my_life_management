<?php

namespace App\Repositories\History\Master;

use App\Interfaces\History\Master\TranslationMstHistInterface;
use App\Models\History\Master\TranslationMstHist;
use Illuminate\Support\Facades\DB;

class TranslationMstHistRepository implements TranslationMstHistInterface
{
    /**
     * @var TranslationMstHist
     */
    protected $model;

    /**
     * TranslationMstHistRepository constructor.
     *
     * @param TranslationMstHist $model
     */
    public function __construct(TranslationMstHist $model)
    {
        $this->model = $model;
    }

    /**
     * Get list of translation history
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

        if (isset($payload['translation_mst_id'])) {
            $query->where('translation_mst_id', $payload['translation_mst_id']);
        }

        if (isset($payload['language_id'])) {
            $query->where('language_id', $payload['language_id']);
        }

        if (isset($payload['original_id'])) {
            $query->where('original_id', $payload['original_id']);
        }

        if (isset($payload['value'])) {
            $query->where('value', 'like', '%' . $payload['value'] . '%');
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
     * Get translation history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Create translation history
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload)
    {
        return $this->model->create($payload);
    }

    /**
     * Get translation history by translation ID
     *
     * @param int $translationId
     * @return mixed
     */
    public function getByTranslationId(int $translationId)
    {
        return $this->model->where('translation_mst_id', $translationId)->orderBy('id', 'desc')->get();
    }

    /**
     * Get translation history by language ID
     *
     * @param int $languageId
     * @return mixed
     */
    public function getByLanguageId(int $languageId)
    {
        return $this->model->where('language_id', $languageId)->orderBy('id', 'desc')->get();
    }

    /**
     * Get translation history by original ID
     *
     * @param int $originalId
     * @return mixed
     */
    public function getByOriginalId(int $originalId)
    {
        return $this->model->where('original_id', $originalId)->orderBy('id', 'desc')->get();
    }
}