<?php

namespace Database\Factories\Master;

use App\Models\Master\Admin;
use App\Models\Master\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\AdminDepartment>
 */
class AdminDepartmentFactory extends Factory
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
            'dept_id' => fake()->randomElement(Department::pluck('id')->toArray()),
        ];
    }
}
