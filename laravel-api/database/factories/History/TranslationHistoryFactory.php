<?php

namespace Database\Factories\History;

use App\Models\Master\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\TranslationHistory>
 */
class TranslationHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $translation = fake()->randomElement(Translation::all()->toArray());

        return [
            'translation_id' => $translation->id,
            'original_translator_id' => $translation->original_translator_id,
            'translate' => $translation->translate,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
