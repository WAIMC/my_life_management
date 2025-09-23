<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Language\LanguageMstListRequest;
use App\Http\Requests\Master\Language\LanguageMstStoreRequest;
use App\Http\Requests\Master\Language\LanguageMstUpdateRequest;
use App\Http\Requests\Master\Language\LanguageMstDeleteRequest;
use App\Services\Master\LanguageMstService;
use Illuminate\Http\JsonResponse;

class LanguageMstController extends Controller
{
    /**
     * @var LanguageMstService
     */
    protected $languageMstService;

    /**
     * LanguageMstController constructor.
     *
     * @param LanguageMstService $languageMstService
     */
    public function __construct(LanguageMstService $languageMstService)
    {
        $this->languageMstService = $languageMstService;
    }

    /**
     * Display a listing of the languages.
     *
     * @param LanguageMstListRequest $request
     * @return JsonResponse
     */
    public function index(LanguageMstListRequest $request): JsonResponse
    {
        $result = $this->languageMstService->getAll($request->validated());
        return response()->json($result);
    }

    /**
     * Display the specified language.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->languageMstService->getById($id);
        return response()->json($result);
    }

    /**
     * Store a newly created language.
     *
     * @param LanguageMstStoreRequest $request
     * @return JsonResponse
     */
    public function store(LanguageMstStoreRequest $request): JsonResponse
    {
        $result = $this->languageMstService->create($request->validated());
        return response()->json($result);
    }

    /**
     * Update the specified language.
     *
     * @param LanguageMstUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(LanguageMstUpdateRequest $request, int $id): JsonResponse
    {
        $result = $this->languageMstService->update($request->validated(), $id);
        return response()->json($result);
    }

    /**
     * Remove the specified language.
     *
     * @param LanguageMstDeleteRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(LanguageMstDeleteRequest $request, int $id): JsonResponse
    {
        $result = $this->languageMstService->delete($id);
        return response()->json($result);
    }
}
