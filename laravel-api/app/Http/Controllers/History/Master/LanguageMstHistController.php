<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Requests\History\Master\LanguageMstHist\ListLanguageMstHistRequest;
use App\Http\Requests\History\Master\LanguageMstHist\StoreLanguageMstHistRequest;
use App\Http\Requests\History\Master\LanguageMstHist\UpdateLanguageMstHistRequest;
use App\Http\Requests\History\Master\LanguageMstHist\DeleteLanguageMstHistRequest;
use App\Services\History\Master\LanguageMstHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageMstHistController extends Controller
{
    public function __construct(
        protected LanguageMstHistService $languageMstHist
    )
    {
    }
    
    /**
     * LanguageMstHist list
     *
     * @param ListLanguageMstHistRequest $request
     * @return JsonResource
     */
    public function list(ListLanguageMstHistRequest $request): JsonResource
    {
        return $this->languageMstHist->list($request->all());
    }

    /**
     * Store language mst hist
     *
     * @param StoreLanguageMstHistRequest $request
     * @return int
     */
    public function store(StoreLanguageMstHistRequest $request): int
    {
        return $this->languageMstHist->store($request->all());
    }

    /**
     * Update language mst hist
     *
     * @param UpdateLanguageMstHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateLanguageMstHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->languageMstHist->update($payload);
    }

    /**
     * Delete language mst hist
     *
     * @param DeleteLanguageMstHistRequest $request
     * @return void
     */
    public function delete(DeleteLanguageMstHistRequest $request): void
    {
        $this->languageMstHist->delete($request->all());
    }
}
