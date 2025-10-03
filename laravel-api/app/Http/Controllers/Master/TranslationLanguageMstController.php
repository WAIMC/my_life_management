<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\TranslationLanguageMst\ListTranslationLanguageMstRequest;
use App\Http\Requests\Master\TranslationLanguageMst\UpdateTranslationLanguageMstRequest;
use App\Services\Master\TranslationLanguageMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationLanguageMstController extends Controller
{
    public function __construct(
        protected TranslationLanguageMstService $translationLanguageMst
    )
    {
    }
    
    /**
     * TranslationLanguageMst list
     *
     * @param ListTranslationLanguageMstRequest $request
     * @return JsonResource
     */
    public function list(ListTranslationLanguageMstRequest $request): JsonResource
    {
        return $this->translationLanguageMst->list($request->all());
    }

    /**
     * Update translation language mst
     *
     * @param UpdateTranslationLanguageMstRequest $request
     * @return bool
     */
    public function update(UpdateTranslationLanguageMstRequest $request): bool
    {
        return $this->translationLanguageMst->update($request->all());
    }
}
