<?php

namespace App\Http\Resources\Management;

use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerMgmtResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'link' => $this->link,
            'image' => $this->image,
            'position' => $this->position,
            'status' => $this->status,
            'status_text' => $this->getStatusText(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'history' => $this->whenLoaded('history'),
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
            StatusEnum::DRAFT => 'Draft',
            StatusEnum::PUBLISHED => 'Published',
            StatusEnum::ARCHIVED => 'Archived',
            default => 'Unknown',
        };
    }
}
