<?php

namespace Database\Factories\Master;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\SkillDescription>
 */
class SkillDescriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->title();
        $summary = fake()->text();
        $article = fake()->text();

        return [
            'parent' => 0,
            'title' => (strlen($title) > 100) ? substr($title, 0, 100) : $title,
            'summary' => (strlen($summary) > 255) ? substr($summary, 0, 255) : $summary,
            'article' => (strlen($article) > 255) ? substr($article, 0, 255) : $article,
            'status' => fake()->random_int(0, 10),
            'rank_order' => fake()->random_int(0, 255),
            'is_display' => false,
        ];
    }
}
