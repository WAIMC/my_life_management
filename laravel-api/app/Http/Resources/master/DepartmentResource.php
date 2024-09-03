<?php

namespace App\Http\Resources\master;

use App\constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id'        => (int)$this->id,
      'code'      => (string)$this->code,
      'name'      => (string)$this->name,
      'status'    => (int)$this->status,
      'update_at' => date(CommonVal::DATE_FORMAT, strtotime($this->updated_at))
    ];
  }
}
