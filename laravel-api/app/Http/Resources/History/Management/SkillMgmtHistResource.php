<?php

namespace App\Http\Resources\History\Management;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillMgmtHistResource extends JsonResource
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
            'skill_mgmt_id' => $this->skill_mgmt_id,
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
        ];
    }
}
