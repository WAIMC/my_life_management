<?php

namespace Database\Factories\History;

use App\Models\Master\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\RoleHistory>
 */
class RoleHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = fake()->randomElement(Role::all()->toArray());

        return [
            'role_id' => $role->id,
            'name' => $role->name,
            'permission' => $role->permission,
            'is_active' => $role->is_active,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
