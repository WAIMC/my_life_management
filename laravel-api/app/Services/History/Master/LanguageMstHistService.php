<?php

namespace App\Services\History\Master;

use App\Http\Resources\History\Master\LanguageMstHistResource;
use App\Interfaces\History\Master\LanguageMstHistInterface;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class LanguageMstHistService
{
    /**
     * @var LanguageMstHistInterface
     */
    protected LanguageMstHistInterface $languageMstHistRepository;

    /**
     * LanguageMstHistService constructor.
     *
     * @param LanguageMstHistInterface $languageMstHistRepository
     */
    public function __construct(LanguageMstHistInterface $languageMstHistRepository)
    {
        $this->languageMstHistRepository = $languageMstHistRepository;
    }

    /**
     * Get list of language history
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getList(array $payload): AnonymousResourceCollection
    {
        $result = $this->languageMstHistRepository->getList($payload);
        return LanguageMstHistResource::collection($result);
    }

    /**
     * Get language history by ID
     *
     * @param int $id
     * @return LanguageMstHistResource
     */
    public function getById(int $id): LanguageMstHistResource
    {
        $languageHist = $this->languageMstHistRepository->getById($id);
        return new LanguageMstHistResource($languageHist);
    }

    /**
     * Create language history
     *
     * @param array $payload
     * @return LanguageMstHistResource
     * @throws Exception
     */
    public function store(array $payload): LanguageMstHistResource
    {
        try {
            DB::beginTransaction();

            $languageHist = $this->languageMstHistRepository->create($payload);

            DB::commit();

            return new LanguageMstHistResource($languageHist);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update language history
     *
     * @param array $payload
     * @param int $id
     * @return LanguageMstHistResource
     * @throws Exception
     */
    public function update(array $payload, int $id): LanguageMstHistResource
    {
        try {
            DB::beginTransaction();

            $languageHist = $this->languageMstHistRepository->update($payload, $id);

            DB::commit();

            return new LanguageMstHistResource($languageHist);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete language history
     *
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function delete(int $id): mixed
    {
        try {
            DB::beginTransaction();

            $result = $this->languageMstHistRepository->delete($id);

            DB::commit();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
