<?php

namespace Database\Factories\History;

use App\Models\Master\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\AdminHistory>
 */
class AdminHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $admin = fake()->randomElement(Admin::all()->toArray());

        return [
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'user_name' => $admin->user_name,
            'password' => $admin->password,
            'first_name' => $admin->first_name,
            'last_name' => $admin->last_name,
            'address' => $admin->address,
            'phone_number' => $admin->phone_number,
            'birth' => $admin->birth,
            'gender' => $admin->gender,
            'status' => $admin->status,
            'is_active' => $admin->is_active,
            'avatar' => $admin->avatar,
            'email_verified_at' => $admin->email_verified_at,
            'remember_token' => $admin->remember_token,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
