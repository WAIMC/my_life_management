<?php

namespace App\Http\Controllers;

use App\Http\Requests\Docs\GetCategoriesRequest;
use App\Http\Requests\Docs\GetEntriesByCategoryRequest;
use App\Http\Requests\Docs\GetEntryDetailRequest;
use App\Http\Requests\Docs\SearchDocsRequest;
use App\Services\DocsService;
use Illuminate\Http\Resources\Json\JsonResource;

class DocsController extends Controller
{
    public function __construct(
        protected DocsService $docsService
    ) {
    }

    /**
     * Get all displayable categories for documentation
     *
     * @param GetCategoriesRequest $request
     * @return JsonResource
     */
    public function getCategories(GetCategoriesRequest $request): JsonResource
    {
        return $this->docsService->getCategories($request->all());
    }

    /**
     * Get entries by category slug
     *
     * @param GetEntriesByCategoryRequest $request
     * @param string $slug
     * @return JsonResource
     */
    public function getEntriesByCategory(GetEntriesByCategoryRequest $request, string $slug): JsonResource
    {
        return $this->docsService->getEntriesByCategory($slug, $request->all());
    }

    /**
     * Get entry detail with descriptions
     *
     * @param GetEntryDetailRequest $request
     * @param string $slug
     * @return JsonResource
     */
    public function getEntryDetail(GetEntryDetailRequest $request, string $slug): JsonResource
    {
        return $this->docsService->getEntryDetail($slug, $request->all());
    }

    /**
     * Global search across categories, entries, and descriptions
     *
     * @param SearchDocsRequest $request
     * @return JsonResource
     */
    public function search(SearchDocsRequest $request): JsonResource
    {
        return $this->docsService->search($request->all());
    }
}
