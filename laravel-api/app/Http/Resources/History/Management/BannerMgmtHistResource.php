<?php

namespace App\Http\Resources\History\Management;

use App\Enums\ActionType;
use App\Enums\BannerStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerMgmtHistResource extends JsonResource
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
            'banner_mgmt_id' => $this->banner_mgmt_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'link' => $this->link,
            'image' => $this->image,
            'position' => $this->position,
            'status' => $this->status,
            'status_text' => $this->getStatusText(),
            'action' => $this->action,
            'action_text' => $this->getActionText(),
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'banner' => $this->whenLoaded('banner'),
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
            BannerStatus::DRAFT => 'Draft',
            BannerStatus::PUBLISHED => 'Published',
            BannerStatus::ARCHIVED => 'Archived',
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
