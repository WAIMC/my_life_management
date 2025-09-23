<?php

namespace App\Services\Master;

use App\Interfaces\Master\LanguageMstInterface;
use App\Http\Resources\Master\LanguageMstResource;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LanguageMstService
{
    /**
     * @var LanguageMstInterface
     */
    protected $languageMstRepository;

    /**
     * LanguageMstService constructor.
     *
     * @param LanguageMstInterface $languageMstRepository
     */
    public function __construct(LanguageMstInterface $languageMstRepository)
    {
        $this->languageMstRepository = $languageMstRepository;
    }

    /**
     * Get all languages
     *
     * @param array $payload
     * @return array
     */
    public function getAll(array $payload): array
    {
        try {
            $languages = $this->languageMstRepository->getAll($payload);
            return [
                'success' => true,
                'data' => LanguageMstResource::collection($languages),
                'pagination' => [
                    'total' => $languages->total(),
                    'per_page' => $languages->perPage(),
                    'current_page' => $languages->currentPage(),
                    'last_page' => $languages->lastPage(),
                ],
                'message' => 'Languages retrieved successfully',
            ];
        } catch (Exception $e) {
            Log::error('Error retrieving languages: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to retrieve languages',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get language by ID
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        try {
            $language = $this->languageMstRepository->getById($id);
            return [
                'success' => true,
                'data' => new LanguageMstResource($language),
                'message' => 'Language retrieved successfully',
            ];
        } catch (Exception $e) {
            Log::error('Error retrieving language: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to retrieve language',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create new language
     *
     * @param array $payload
     * @return array
     */
    public function create(array $payload): array
    {
        try {
            $language = $this->languageMstRepository->create($payload);
            return [
                'success' => true,
                'data' => new LanguageMstResource($language),
                'message' => 'Language created successfully',
            ];
        } catch (Exception $e) {
            Log::error('Error creating language: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to create language',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Update language
     *
     * @param array $payload
     * @param int $id
     * @return array
     */
    public function update(array $payload, int $id): array
    {
        try {
            $language = $this->languageMstRepository->update($payload, $id);
            return [
                'success' => true,
                'data' => new LanguageMstResource($language),
                'message' => 'Language updated successfully',
            ];
        } catch (Exception $e) {
            Log::error('Error updating language: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to update language',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete language
     *
     * @param int $id
     * @return array
     */
    public function delete(int $id): array
    {
        try {
            $this->languageMstRepository->delete($id);
            return [
                'success' => true,
                'message' => 'Language deleted successfully',
            ];
        } catch (Exception $e) {
            Log::error('Error deleting language: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to delete language',
                'error' => $e->getMessage(),
            ];
        }
    }
}
