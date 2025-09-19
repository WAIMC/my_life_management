<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentManagementMstResource extends JsonResource
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
            'departmentId' => $this->department_id,
            'policyDepartmentId' => $this->policy_department_id,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'department' => $this->whenLoaded('department', function() {
                return new DepartmentMstResource($this->department);
            }),
            'policyDepartment' => $this->whenLoaded('policyDepartment', function() {
                return new PolicyDepartmentMstResource($this->policyDepartment);
            }),
        ];
    }
}
