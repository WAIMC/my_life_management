<?php

namespace App\Http\Resources\History\Master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\ActionType;

class PolicyDepartmentMstHistResource extends JsonResource
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
            'policy_department_mst_id' => $this->policy_department_mst_id,
            'table_name' => $this->table_name,
            'row_id' => $this->row_id,
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
