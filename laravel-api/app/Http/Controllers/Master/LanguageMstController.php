<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\LanguageMst\ListLanguageMstRequest;
use App\Http\Requests\Master\LanguageMst\StoreLanguageMstRequest;
use App\Http\Requests\Master\LanguageMst\UpdateLanguageMstRequest;
use App\Http\Requests\Master\LanguageMst\DeleteLanguageMstRequest;
use App\Services\Master\LanguageMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageMstController extends Controller
{
    public function __construct(
        protected LanguageMstService $languageMst
    )
    {
    }
    
    /**
     * LanguageMst list
     *
     * @param ListLanguageMstRequest $request
     * @return JsonResource
     */
    public function list(ListLanguageMstRequest $request): JsonResource
    {
        return $this->languageMst->list($request->all());
    }

    /**
     * Store language mst
     *
     * @param StoreLanguageMstRequest $request
     * @return int
     */
    public function store(StoreLanguageMstRequest $request): int
    {
        return $this->languageMst->store($request->all());
    }

    /**
     * Update language mst
     *
     * @param UpdateLanguageMstRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateLanguageMstRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->languageMst->update($payload);
    }

    /**
     * Delete language mst
     *
     * @param DeleteLanguageMstRequest $request
     * @return void
     */
    public function delete(DeleteLanguageMstRequest $request): void
    {
        $this->languageMst->delete($request->all());
    }
}
