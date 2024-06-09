<?php

namespace Database\Factories\History;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Master\Language;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\LanguageHistory>
 */
class LanguageHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $language = fake()->randomElement(Language::all()->toArray());

        return [
            'language_id' => $language->id,
            'abbreviation' => $language->abbreviation,
            'name' => $language->name,
            'is_active' => $language->is_active,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
