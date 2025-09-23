<?php

namespace App\Services\Master;

use App\Http\Resources\Master\OriginalTranslatorMstResource;
use App\Interfaces\Master\OriginalTranslatorMstInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class OriginalTranslatorMstService
{
    /**
     * @var OriginalTranslatorMstInterface
     */
    protected $originalTranslatorMstRepository;

    /**
     * OriginalTranslatorMstService constructor.
     *
     * @param OriginalTranslatorMstInterface $originalTranslatorMstRepository
     */
    public function __construct(OriginalTranslatorMstInterface $originalTranslatorMstRepository)
    {
        $this->originalTranslatorMstRepository = $originalTranslatorMstRepository;
    }

    /**
     * Get list of original translators
     *
     * @param array $payload
     * @return mixed
     */
    public function getList(array $payload)
    {
        $result = $this->originalTranslatorMstRepository->getList($payload);
        return OriginalTranslatorMstResource::collection($result);
    }

    /**
     * Get original translator by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        $originalTranslator = $this->originalTranslatorMstRepository->getById($id);
        return new OriginalTranslatorMstResource($originalTranslator);
    }

    /**
     * Create original translator
     *
     * @param array $payload
     * @return mixed
     * @throws Exception
     */
    public function create(array $payload)
    {
        try {
            DB::beginTransaction();

            $originalTranslator = $this->originalTranslatorMstRepository->create($payload);

            DB::commit();

            return new OriginalTranslatorMstResource($originalTranslator);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update original translator
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function update(array $payload, int $id)
    {
        try {
            DB::beginTransaction();

            $originalTranslator = $this->originalTranslatorMstRepository->update($payload, $id);

            DB::commit();

            return new OriginalTranslatorMstResource($originalTranslator);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete original translator
     *
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function delete(int $id)
    {
        try {
            // Check if translator is being used in translations
            $originalTranslator = $this->originalTranslatorMstRepository->getById($id);

            if ($originalTranslator->translations()->count() > 0) {
                throw new Exception("Cannot delete original translator that has associated translations.");
            }

            DB::beginTransaction();

            $result = $this->originalTranslatorMstRepository->delete($id);

            DB::commit();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
