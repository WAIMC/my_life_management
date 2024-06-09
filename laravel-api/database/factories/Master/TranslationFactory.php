<?php

namespace Database\Factories\Master;

use App\Models\Master\OriginalTranslator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $translate = fake()->text();

        return [
            'original_translator_id' => fake()->randomElement(OriginalTranslator::pluck('id')->toArray()),
            'translate' => (strlen($translate) > 255) ? substr($translate, 0, 255) : $translate,
        ];
    }
}
