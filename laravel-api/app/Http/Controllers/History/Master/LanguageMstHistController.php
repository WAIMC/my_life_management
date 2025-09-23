<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Master\Language\DeleteLanguageMstHistRequest;
use App\Http\Requests\History\Master\Language\LanguageMstHistListRequest;
use App\Http\Requests\History\Master\Language\StoreLanguageMstHistRequest;
use App\Http\Requests\History\Master\Language\UpdateLanguageMstHistRequest;
use App\Http\Resources\History\Master\LanguageMstHistResource;
use App\Services\History\Master\LanguageMstHistService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LanguageMstHistController extends Controller
{
    protected LanguageMstHistService $departmentMstHistService;

    /**
     * Constructor
     *
     * @param LanguageMstHistService $departmentMstHistService
     */
    public function __construct(LanguageMstHistService $departmentMstHistService)
    {
        $this->departmentMstHistService = $departmentMstHistService;
    }

    /**
     * Get a listing of language history records
     *
     * @param LanguageMstHistListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(LanguageMstHistListRequest $request): AnonymousResourceCollection
    {
        return $this->departmentMstHistService->getList($request->validated());
    }

    /**
     * Get language history by ID
     *
     * @param string $id
     * @return LanguageMstHistResource
     */
    public function show(string $id): LanguageMstHistResource
    {
        return $this->departmentMstHistService->getById((int)$id);
    }

    /**
     * Create a new language history record
     *
     * @param StoreLanguageMstHistRequest $request
     * @return LanguageMstHistResource
     * @throws Exception
     */
    public function store(StoreLanguageMstHistRequest $request): LanguageMstHistResource
    {
        $data = $request->validated();
        $data['created_at'] = now()->format('Y-m-d H:i:s');

        return $this->departmentMstHistService->store($data);
    }

    /**
     * Update a new language history record
     *
     * @param UpdateLanguageMstHistRequest $request
     * @param string $id
     * @return LanguageMstHistResource
     * @throws Exception
     */
    public function update(UpdateLanguageMstHistRequest $request, string $id): LanguageMstHistResource
    {
        return $this->departmentMstHistService->update($request->all(), $id);
    }

    /**
     * Update a new language history record
     *
     * @param DeleteLanguageMstHistRequest $request
     * @param string $id
     * @return bool
     * @throws Exception
     */
    public function delete(DeleteLanguageMstHistRequest $request, string $id): bool
    {
        return $this->departmentMstHistService->delete((int)$id);
    }
}
