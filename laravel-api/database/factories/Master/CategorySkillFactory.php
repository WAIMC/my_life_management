<?php

namespace Database\Factories\Master;

use App\Models\Master\Category;
use App\Models\Master\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\CategorySkill>
 */
class CategorySkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => fake()->randomElement(Category::pluck('id')->toArray()),
            'skill_id' => fake()->randomElement(Skill::pluck('id')->toArray()),
        ];
    }
}
