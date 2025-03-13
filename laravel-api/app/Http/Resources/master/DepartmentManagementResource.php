<?php

namespace App\Http\Resources\master;

use App\constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentManagementResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'department_id'        => (int)$this->department_id,
      'code'                 => (string)$this->code,
      'name'                 => (string)$this->name,
      'department_status'    => (int)$this->department_status,
      'policy_department_id' => (int)$this->policy_department_id,
      'table_name'           => (string)$this->table_name,
      'row_id'               => (int)$this->row_id,
      'updated_at'           => date(CommonVal::DATE_FORMAT, strtotime($this->updated_at))
    ];
  }
}
