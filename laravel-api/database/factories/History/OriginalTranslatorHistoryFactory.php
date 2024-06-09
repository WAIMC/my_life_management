<?php

namespace Database\Factories\History;

use App\Models\Master\OriginalTranslator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\OriginalTranslatorHistory>
 */
class OriginalTranslatorHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $originalTranslator = fake()->randomElement(OriginalTranslator::all()->toArray());

        return [
            'original_translator_id' => $originalTranslator->id,
            'table_name' => $originalTranslator->table_name,
            'column_name' => $originalTranslator->column_name,
            'field_id' => $originalTranslator->field_id,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
