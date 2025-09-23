<?php

namespace App\Http\Requests\History\Master\PolicyDepartment;

class StorePolicyDepartmentMstHistRequest extends PolicyDepartmentMstHistRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'policy_department_mst_id' => 'required|integer|exists:policy_department_mst,id',
            'table_name' => 'nullable|string|max:20',
            'row_id' => 'nullable|integer',
            'action' => 'required|integer|in:1,2,3',
            'author_id' => 'required|integer|exists:admin_mst,id',
        ];
    }
}
