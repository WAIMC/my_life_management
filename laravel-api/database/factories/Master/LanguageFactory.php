<?php

namespace Database\Factories\Master;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Language>
 */
class LanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $abbreviation = fake()->languageCode();

        return [
            'abbreviation' => (strlen($abbreviation) > 10) ? substr($abbreviation, 0, 10) : $abbreviation,
            'name' => fake()->currencyCode(1, 30),
            'is_active' => false,
        ];
    }
}
