<?php

namespace Database\Factories\History;

use App\Models\Master\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\SkillHistory>
 */
class SkillHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $skill = fake()->randomElement(Skill::all()->toArray());

        return [
            'skill_id' => $skill->id,
            'parent_id' => $skill->parent->id,
            'name' => $skill->name,
            'slug' => $skill->name,
            'status' => $skill->status,
            'rank_order' => $skill->rank_order,
            'is_display' => $skill->is_display,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
