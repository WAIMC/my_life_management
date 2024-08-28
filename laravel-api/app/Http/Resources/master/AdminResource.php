<?php

namespace App\Http\Resources\master;

use App\constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id'           => (int)$this->id,
      'email'        => (string)$this->email,
      'user_name'    => (string)$this->user_name,
      'first_name'   => (string)$this->first_name,
      'last_name'    => (string)$this->last_name,
      'address'      => (string)$this->address,
      'phone_number' => (string)$this->phone_number,
      'birth'        => (string)$this->birth,
      'gender'       => (int)$this->gender,
      'status'       => (int)$this->status,
      'is_active'    => (bool)$this->is_active,
      'avatar'       => (string)$this->avatar,
      'updated_at'   => (string)date(CommonVal::DATE_FORMAT, strtotime($this->updated_at)),
    ];
  }
}
