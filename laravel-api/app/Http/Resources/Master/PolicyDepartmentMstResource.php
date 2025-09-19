<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyDepartmentMstResource extends JsonResource
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
            'tableName' => $this->table_name,
            'rowId' => $this->row_id,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'departmentManagements' => $this->whenLoaded('departmentManagements'),
        ];
    }
}
