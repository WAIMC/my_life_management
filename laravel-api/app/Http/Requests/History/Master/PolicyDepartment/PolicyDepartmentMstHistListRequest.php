<?php

namespace App\Http\Requests\History\Master\PolicyDepartment;

class PolicyDepartmentMstHistListRequest extends PolicyDepartmentMstHistRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'policy_department_mst_id' => 'sometimes|integer|exists:policy_department_mst,id',
            'table_name' => 'sometimes|string|max:20',
            'row_id' => 'sometimes|integer',
            'action' => 'sometimes|integer|in:1,2,3',
            'author_id' => 'sometimes|integer|exists:admin_mst,id',
            'per_page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:id,policy_department_mst_id,table_name,row_id,action,author_id,created_at',
            'sort_order' => 'sometimes|string|in:asc,desc',
        ];
    }
}
