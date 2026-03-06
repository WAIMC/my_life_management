<?php

namespace App\Http\Resources\Management;

use App\Constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryDescriptionMgmtResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int)$this->id,
            'parent_id' => (int)$this->parent_id,
            'title' => (string)$this->title,
            'summary' => (string)$this->summary,
            'article' => (string)$this->article,
            'status' => (string)$this->status,
            'is_display' => (bool)$this->is_display,
            'rank_order' => (string)$this->rank_order,
            'entry_mgmt_id' => (int)$this->entry_mgmt_id,
            'is_delete' => (bool)$this->is_delete,
            'updated_at' => (string)date(CommonVal::DATE_FORMAT, strtotime($this->updated_at)),
        ];
    }
}
