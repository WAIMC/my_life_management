<?php

namespace App\Http\Resources\master;

use App\constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiRoleResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'api_id'          => (int)$this->api_id,
      'type_name'       => (string)$this->type_name,
      'type'            => (int)$this->type,
      'name'            => (string)$this->name,
      'path'            => (string)$this->path,
      'is_active'       => (bool)$this->is_active,
      'role_id'         => (int)$this->role_id,
      'role_name'       => (string)$this->role_name,
      'role_permission' => (string)$this->role_permission,
      'role_status'     => (int)$this->role_status,
      'updated_at'      => date(CommonVal::DATE_FORMAT, strtotime($this->updated_at))
    ];
  }
}
