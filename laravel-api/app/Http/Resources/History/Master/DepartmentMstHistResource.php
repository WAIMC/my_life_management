<?php

namespace App\Http\Resources\History\Master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentMstHistResource extends JsonResource
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
            'department_mst_id' => $this->department_mst_id,
            'code' => $this->code,
            'name' => $this->name,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'action' => $this->action,
            'action_text' => $this->action_text,
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'department' => $this->whenLoaded('department'),
        ];
    }
}
