<?php

namespace Database\Factories\Master;

use App\Models\Master\Admin;
use App\Models\Master\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\AdminRole>
 */
class AdminRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'admin_id' => fake()->randomElement(Admin::pluck('id')->toArray()),
            'role_id' => fake()->randomElement(Role::pluck('id')->toArray()),
        ];
    }
}
