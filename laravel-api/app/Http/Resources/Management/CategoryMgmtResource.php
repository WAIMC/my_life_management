<?php

namespace App\Http\Resources\Management;

use App\Enums\CategoryStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryMgmtResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
            'status_text' => $this->getStatusText(),
            'is_display' => $this->is_display,
            'rank_order' => $this->rank_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'parent' => $this->when($this->parent_id > 0, new CategoryMgmtResource($this->whenLoaded('parent'))),
            'children' => CategoryMgmtResource::collection($this->whenLoaded('children')),
            'products_count' => $this->when($this->relationLoaded('products'), $this->products->count()),
            'projects_count' => $this->when($this->relationLoaded('projects'), $this->projects->count()),
            'skills' => SkillMgmtResource::collection($this->whenLoaded('skills')),
            'history' => CategoryMgmtHistResource::collection($this->whenLoaded('history')),
        ];
    }

    /**
     * Get the text representation of the status
     *
     * @return string
     */
    private function getStatusText(): string
    {
        return match ($this->status) {
            CategoryStatus::INACTIVE => 'Inactive',
            CategoryStatus::ACTIVE => 'Active',
            CategoryStatus::ARCHIVED => 'Archived',
            default => 'Unknown',
        };
    }
}
