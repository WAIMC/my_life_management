<?php

namespace App\Http\Resources\History\Master;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\ActionType;
use JsonSerializable;

class OriginalTranslatorMstHistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray(Request $request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'original_translator_mst_id' => $this->original_translator_mst_id,
            'table' => $this->table,
            'column' => $this->column,
            'field_id' => $this->field_id,
            'action' => $this->action,
            'action_text' => $this->getActionText(),
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Get action text based on action code.
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
