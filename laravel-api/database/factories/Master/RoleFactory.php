<?php

namespace Database\Factories\Master;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        $permission = fake()->name();

        return [
            'name' => (strlen($name) > 30) ? substr($name, 0, 30) : $name,
            'permission' => (strlen($permission) > 50) ? substr($permission, 0, 50) : $permission,
            'is_active' => false,
        ];
    }
}
