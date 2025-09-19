<?php

namespace App\Http\Resources\History\Master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\ActionType;

class ApiMstHistResource extends JsonResource
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
            'api_mst_id' => $this->api_mst_id,
            'type' => $this->type,
            'name' => $this->name,
            'path' => $this->path,
            'is_active' => $this->is_active,
            'feature_id' => $this->feature_id,
            'action' => $this->action,
            'action_text' => $this->getActionText(),
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'api' => $this->whenLoaded('apiMst'),
            'feature' => $this->whenLoaded('feature'),
            'author' => $this->whenLoaded('author'),
        ];
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
