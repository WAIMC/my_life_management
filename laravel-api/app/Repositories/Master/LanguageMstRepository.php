<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\LanguageMstInterface;
use App\Models\Master\LanguageMst;
use Exception;
use Illuminate\Support\Facades\DB;

class LanguageMstRepository implements LanguageMstInterface
{
    /**
     * @var LanguageMst
     */
    protected $model;

    /**
     * LanguageMstRepository constructor.
     *
     * @param LanguageMst $model
     */
    public function __construct(LanguageMst $model)
    {
        $this->model = $model;
    }

    /**
     * Get all languages
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload)
    {
        $query = $this->model->query();

        // Apply filters if provided
        if (isset($payload['abbreviation'])) {
            $query->where('abbreviation', 'like', '%' . $payload['abbreviation'] . '%');
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['is_active'])) {
            $query->where('is_active', $payload['is_active']);
        }

        // Apply sorting
        $sortBy = $payload['sort_by'] ?? 'id';
        $sortOrder = $payload['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);

        // Apply pagination
        $perPage = $payload['per_page'] ?? 10;
        return $query->paginate($perPage);
    }

    /**
     * Get language by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create new language
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload)
    {
        return $this->model->create($payload);
    }

    /**
     * Update language
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function update(array $payload, int $id)
    {
        $language = $this->model->findOrFail($id);
        
        if (!$language) {
            throw new Exception('Language not found');
        }

        if (isset($payload['abbreviation'])) {
            $language->abbreviation = $payload['abbreviation'];
        }

        if (isset($payload['name'])) {
            $language->name = $payload['name'];
        }

        if (isset($payload['is_active'])) {
            $language->is_active = $payload['is_active'];
        }

        $language->save();
        return $language;
    }

    /**
     * Delete language
     *
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function delete(int $id)
    {
        $language = $this->model->findOrFail($id);
        
        if (!$language) {
            throw new Exception('Language not found');
        }

        return $language->delete();
    }
}
