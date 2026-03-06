<?php

namespace App\Services;

use App\Interfaces\Management\CategoryMgmtInterface;
use App\Interfaces\Management\EntryMgmtInterface;
use App\Interfaces\Management\EntryDescriptionMgmtInterface;
use App\Http\Resources\Docs\CategoryResource;
use App\Http\Resources\Docs\EntryResource;
use App\Http\Resources\Docs\EntryDetailResource;
use App\Http\Resources\Docs\SearchResultResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class DocsService
{
    public function __construct(
        protected CategoryMgmtInterface $categoryMgmt,
        protected EntryMgmtInterface $entryMgmt,
        protected EntryDescriptionMgmtInterface $entryDescriptionMgmt
    ) {
    }

    /**
     * Get all displayable categories
     *
     * @param array $payload
     * @return JsonResource
     */
    public function getCategories(array $payload): JsonResource
    {
        $categories = $this->categoryMgmt->getDisplayableCategories();
        
        return CategoryResource::collection($categories);
    }

    /**
     * Get entries by category slug
     *
     * @param string $slug
     * @param array $payload
     * @return JsonResource
     */
    public function getEntriesByCategory(string $slug, array $payload): JsonResource
    {
        $entries = $this->entryMgmt->getEntriesByCategorySlug($slug);
        
        return EntryResource::collection($entries);
    }

    /**
     * Get entry detail with descriptions
     *
     * @param string $slug
     * @param array $payload
     * @return JsonResource
     */
    public function getEntryDetail(string $slug, array $payload): JsonResource
    {
        $entry = $this->entryMgmt->getEntryDetailBySlug($slug);
        
        return new EntryDetailResource($entry);
    }

    /**
     * Global search
     *
     * @param array $payload
     * @return JsonResource
     */
    public function search(array $payload): JsonResource
    {
        $query = $payload['q'] ?? '';
        
        $results = [
            'categories' => $this->categoryMgmt->searchCategories($query),
            'entries' => $this->entryMgmt->searchEntries($query),
            'descriptions' => $this->entryDescriptionMgmt->searchDescriptions($query),
        ];
        
        return new SearchResultResource($results);
    }
}
