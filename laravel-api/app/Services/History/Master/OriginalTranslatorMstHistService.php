<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\OriginalTranslatorMstHistInterface;
use App\Http\Resources\History\Master\OriginalTranslatorMstHistResource;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OriginalTranslatorMstHistService
{
    /**
     * @var OriginalTranslatorMstHistInterface
     */
    protected OriginalTranslatorMstHistInterface $originalTranslatorMstHistRepository;

    /**
     * OriginalTranslatorMstHistService constructor.
     *
     * @param OriginalTranslatorMstHistInterface $originalTranslatorMstHistRepository
     */
    public function __construct(OriginalTranslatorMstHistInterface $originalTranslatorMstHistRepository)
    {
        $this->originalTranslatorMstHistRepository = $originalTranslatorMstHistRepository;
    }

    /**
     * Get all history records.
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $records = $this->originalTranslatorMstHistRepository->getAll($payload);
        return OriginalTranslatorMstHistResource::collection($records);
    }

    /**
     * Get history record by ID.
     *
     * @param int $id
     * @return OriginalTranslatorMstHistResource
     */
    public function getById(int $id): OriginalTranslatorMstHistResource
    {
        try {
            $record = $this->originalTranslatorMstHistRepository->getById($id);
            return new OriginalTranslatorMstHistResource($record);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('History record not found');
        }
    }

    /**
     * Create new history record.
     *
     * @param array $payload
     * @return OriginalTranslatorMstHistResource
     * @throws Exception
     */
    public function create(array $payload): OriginalTranslatorMstHistResource
    {
        DB::beginTransaction();
        try {
            $record = $this->originalTranslatorMstHistRepository->create($payload);
            DB::commit();
            return new OriginalTranslatorMstHistResource($record);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update history record.
     *
     * @param array $payload
     * @param int $id
     * @return OriginalTranslatorMstHistResource
     * @throws Exception
     */
    public function update(array $payload, int $id): OriginalTranslatorMstHistResource
    {
        DB::beginTransaction();
        try {
            $record = $this->originalTranslatorMstHistRepository->update($payload, $id);
            DB::commit();
            return new OriginalTranslatorMstHistResource($record);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw new ModelNotFoundException('History record not found');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete history record.
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->originalTranslatorMstHistRepository->delete($id);
            DB::commit();
            return $result;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw new ModelNotFoundException('History record not found');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
