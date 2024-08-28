<?php

namespace App\Http\Resources\master;

use App\constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
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
      'type_name'     => (string)$this->type_name,
      'type'          => (int)$this->type,
      'name'          => (string)$this->name,
      'path'          => (string)$this->path,
      'is_active'      => (bool)$this->is_active,
      'feature_id'    => (int)$this->feature_id,
      'feature_name'  => (string)$this->feature_name,
      'feature_group' => (string)$this->feature_group,
      'updated_at'    => date(CommonVal::DATE_FORMAT, strtotime($this->updated_at))
    ];
  }
}
