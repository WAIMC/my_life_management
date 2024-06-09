<?php

namespace Database\Factories\History;

use App\Models\Master\Master\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\UserHistory>
 */
class UserHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = fake()->randomElement(User::all()->toArray());

        return [
            'user_id' => $user->id,
            'email' => $user->email,
            'user_name' => $user->user_name,
            'password' => $user->password,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'address' => $user->address,
            'phone_number' => $user->phone_number,
            'birth' => $user->birth,
            'gender' => $user->gender,
            'status' => $user->status,
            'is_active' => $user->is_active,
            'avatar' => $user->avatar,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
