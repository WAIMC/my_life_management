<?php

namespace Database\Factories\Master;

use App\Models\Master\Language;
use App\Models\Master\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\TranslationLanguage>
 */
class TranslationLanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'translation_id' => fake()->randomElement(Translation::pluck('id')->toArray()),
            'language_id' => fake()->randomElement(Language::pluck('id')->toArray()),
        ];
    }
}
