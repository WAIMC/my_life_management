<?php

namespace App\Http\Resources\master;

use App\constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyDepartmentResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id'            => (int)$this->id,
      'table_name'    => (string)$this->table_name,
      'row_id'         => (int)$this->row_id,
      'updated_at'    => date(CommonVal::DATE_FORMAT, strtotime($this->updated_at))
    ];
  }
}
