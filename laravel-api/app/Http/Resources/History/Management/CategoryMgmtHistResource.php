<?php

namespace App\Http\Resources\History\Management;

use App\Enums\ActionType;
use App\Enums\CategoryStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryMgmtHistResource extends JsonResource
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
            'category_mgmt_id' => $this->category_mgmt_id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
            'status_text' => $this->getStatusText(),
            'is_display' => $this->is_display,
            'rank_order' => $this->rank_order,
            'action' => $this->action,
            'action_text' => $this->getActionText(),
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'category' => $this->whenLoaded('category'),
            'author' => $this->whenLoaded('author'),
        ];
    }

    /**
     * Get the text representation of the status
     *
     * @return string|null
     */
    private function getStatusText(): ?string
    {
        if ($this->status === null) {
            return null;
        }

        return match ($this->status) {
            CategoryStatus::INACTIVE => 'Inactive',
            CategoryStatus::ACTIVE => 'Active',
            CategoryStatus::ARCHIVED => 'Archived',
            default => 'Unknown',
        };
    }

    /**
     * Get the text representation of the action
     *
     * @return string
     */
    private function getActionText(): string
    {
        return match ($this->action) {
            ActionType::CREATE => 'Create',
            ActionType::UPDATE => 'Update',
            ActionType::DELETE => 'Delete',
            default => 'Unknown',
        };
    }
}
