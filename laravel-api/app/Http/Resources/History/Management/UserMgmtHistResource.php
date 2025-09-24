<?php

namespace App\Http\Resources\History\Management;

use App\Enums\ActionEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMgmtHistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_mgmt_id' => $this->user_mgmt_id,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'user_name' => $this->user->user_name,
                    'email' => $this->user->email,
                    'full_name' => $this->user->first_name . ' ' . $this->user->last_name,
                ];
            }),
            'role_id' => $this->role_id,
            'role' => $this->whenLoaded('role', function () {
                return [
                    'id' => $this->role->id,
                    'name' => $this->role->name,
                ];
            }),
            'department_id' => $this->department_id,
            'department' => $this->whenLoaded('department', function () {
                return [
                    'id' => $this->department->id,
                    'name' => $this->department->name,
                ];
            }),
            'email' => $this->email,
            'user_name' => $this->user_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'birth' => $this->birth,
            'gender' => $this->gender,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'avatar' => $this->avatar,
            'email_verified_at' => $this->email_verified_at,
            'action' => $this->action,
            'action_text' => $this->getActionText(),
            'author_id' => $this->author_id,
            'author' => $this->whenLoaded('author', function () {
                return [
                    'id' => $this->author->id,
                    'user_name' => $this->author->user_name,
                    'email' => $this->author->email,
                    'full_name' => $this->author->first_name . ' ' . $this->author->last_name,
                ];
            }),
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Get the action text based on the action code
     *
     * @return string
     */
    protected function getActionText(): string
    {
        switch ($this->action) {
            case ActionEnum::INSERT:
                return 'Created';
            case ActionEnum::UPDATE:
                return 'Updated';
            case ActionEnum::DELETE:
                return 'Deleted';
            default:
                return 'Unknown';
        }
    }
}