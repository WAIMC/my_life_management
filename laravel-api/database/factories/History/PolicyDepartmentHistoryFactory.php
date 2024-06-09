<?php

namespace Database\Factories\History;

use App\Models\Master\PolicyDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\PolicyDepartmentHistory>
 */
class PolicyDepartmentHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $policyDepartment = fake()->randomElement(PolicyDepartment::all()->toArray());

        return [
            'policy_department_id' => $policyDepartment->id,
            'category_id' => $policyDepartment->category_id,
            'skill_id' => $policyDepartment->skill_id,
            'skill_description_id' => $policyDepartment->skill_description_id,
            'slider_id' => $policyDepartment->slider_id,
            'banner_id' => $policyDepartment->banner_id,
            'setting_link_id' => $policyDepartment->setting_link_id,
            'social_id' => $policyDepartment->social_id,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
