<?php

namespace Database\Factories\History;

use App\Models\Master\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\DepartmentHistory>
 */
class DepartmentHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $department = fake()->randomElement(Department::all()->toArray());

        return [
            'department_id' => $department->id,
            'code' => $department->code,
            'name' => $department->name,
            'status' => $department->status,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
