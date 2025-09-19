<?php

namespace App\Http\Resources\History\Master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMstHistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'adminMstId' => $this->admin_mst_id,
            'email' => $this->email,
            'userName' => $this->user_name,
            'password' => $this->password,
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
            'rememberToken' => $this->remember_token,
            'action' => $this->action,
            'authorId' => $this->author_id,
            'createdAt' => $this->created_at,
            'admin' => $this->whenLoaded('adminMst'),
            'author' => $this->whenLoaded('author'),
        ];
    }
}
