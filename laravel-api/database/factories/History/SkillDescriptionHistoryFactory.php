<?php

namespace Database\Factories\History;

use App\Models\Master\SkillDescription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SkillDescriptionHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $skillDescription = fake()->randomElement(SkillDescription::all()->toArray());

        return [
            'skill_description' => $skillDescription->id,
            'parent_id' => $skillDescription->parent_id,
            'title' => $skillDescription->title,
            'summary' => $skillDescription->summary,
            'article' => $skillDescription->article,
            'status' => $skillDescription->status,
            'rank_order' => $skillDescription->rank_order,
            'is_display' => $skillDescription->is_display,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
