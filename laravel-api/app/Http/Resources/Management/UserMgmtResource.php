<?php

namespace App\Http\Resources\Management;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMgmtResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'roleId' => $this->role_id,
            'departmentId' => $this->department_id,
            'email' => $this->email,
            'userName' => $this->user_name,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'address' => $this->address,
            'phoneNumber' => $this->phone_number,
            'birth' => $this->birth,
            'gender' => $this->gender,
            'status' => $this->status,
            'isActive' => $this->is_active,
            'avatar' => $this->avatar,
            'emailVerifiedAt' => $this->email_verified_at,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'role' => $this->whenLoaded('role'),
            'department' => $this->whenLoaded('department')
        ];
    }
}