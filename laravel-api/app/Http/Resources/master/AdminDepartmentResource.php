<?php

namespace App\Http\Resources\master;

use App\constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminDepartmentResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'admin_id'          => (int)$this->admin_id,
      'email'             => (string)$this->email,
      'admin_status'      => (int)$this->admin_status,
      'is_active'         => (bool)$this->is_active,
      'department_id'     => (int)$this->department_id,
      'department_code'   => (string)$this->department_code,
      'department_name'   => (string)$this->department_name,
      'department_status' => (int)$this->department_status,
      'updated_at'        => date(CommonVal::DATE_FORMAT, strtotime($this->updated_at))
    ];
  }
}
