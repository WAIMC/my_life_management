<?php

namespace App\Http\Requests\History\Master\PolicyDepartment;

class UpdatePolicyDepartmentMstHistRequest extends PolicyDepartmentMstHistRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'policy_department_mst_id' => 'sometimes|integer|exists:policy_department_mst,id',
            'table_name' => 'sometimes|nullable|string|max:20',
            'row_id' => 'sometimes|nullable|integer',
            'action' => 'sometimes|integer|in:1,2,3',
            'author_id' => 'sometimes|integer|exists:admin_mst,id',
        ];
    }
}
