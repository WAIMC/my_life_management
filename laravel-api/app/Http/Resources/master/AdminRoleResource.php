<?php

namespace App\Http\Resources\master;

use App\constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminRoleResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'admin_id'        => (int)$this->admin_id,
      'email'           => (string)$this->email,
      'first_name'      => (string)$this->first_name,
      'last_name'       => (string)$this->last_name,
      'status'          => (int)$this->status,
      'role_id'         => (int)$this->role_id,
      'role_name'       => (string)$this->role_name,
      'role_permission' => (string)$this->role_permission,
      'updated_at'      => (string)date(CommonVal::DATE_FORMAT, strtotime($this->updated_at)),
    ];
  }
}
