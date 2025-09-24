<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Master\Translation\DeleteTranslationMstHistRequest;
use App\Http\Requests\History\Master\Translation\StoreTranslationMstHistRequest;
use App\Http\Requests\History\Master\Translation\TranslationMstHistListRequest;
use App\Http\Resources\History\Master\TranslationMstHistResource;
use App\Services\History\Master\TranslationMstHistService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TranslationMstHistController extends Controller
{
    protected TranslationMstHistService $translationMstHistService;

    /**
     * Constructor
     *
     * @param TranslationMstHistService $translationMstHistService
     */
    public function __construct(TranslationMstHistService $translationMstHistService)
    {
        $this->translationMstHistService = $translationMstHistService;
    }

    /**
     * Get a listing of translation history records
     *
     * @param TranslationMstHistListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(TranslationMstHistListRequest $request): AnonymousResourceCollection
    {
        return $this->translationMstHistService->getList($request->validated());
    }

    /**
     * Store a newly created translation history record in storage.
     *
     * @param StoreTranslationMstHistRequest $request
     * @return TranslationMstHistResource
     * @throws Exception
     */
    public function store(StoreTranslationMstHistRequest $request): TranslationMstHistResource
    {
        return $this->translationMstHistService->create($request->validated());
    }

    /**
     * Display the specified translation history record.
     *
     * @param int $id
     * @return TranslationMstHistResource
     * @throws Exception
     */
    public function show(int $id): TranslationMstHistResource
    {
        return $this->translationMstHistService->getById($id);
    }

    /**
     * Display translation history records by translation ID.
     *
     * @param int $translationId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function getByTranslationId(int $translationId): AnonymousResourceCollection
    {
        return $this->translationMstHistService->getByTranslationId($translationId);
    }

    /**
     * Display translation history records by language ID.
     *
     * @param int $languageId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function getByLanguageId(int $languageId): AnonymousResourceCollection
    {
        return $this->translationMstHistService->getByLanguageId($languageId);
    }

    /**
     * Display translation history records by original ID.
     *
     * @param int $originalId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function getByOriginalId(int $originalId): AnonymousResourceCollection
    {
        return $this->translationMstHistService->getByOriginalId($originalId);
    }
}