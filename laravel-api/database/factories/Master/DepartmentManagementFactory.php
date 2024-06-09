<?php

namespace Database\Factories\Master;

use App\Models\Master\Department;
use App\Models\Master\PolicyDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\DepartmentManagement>
 */
class DepartmentManagementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'department_id' => fake()->randomElement(Department::pluck('id')->toArray()),
            'policy_department_id' => fake()->randomElement(PolicyDepartment::pluck('id')->toArray()),
        ];
    }
}
