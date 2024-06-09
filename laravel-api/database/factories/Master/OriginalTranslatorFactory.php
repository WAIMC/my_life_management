<?php

namespace Database\Factories\Master;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\OriginalTranslator>
 */
class OriginalTranslatorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tableName = fake()->name();
        $columnName = fake()->name();

        return [
            'table_name' => (strlen($tableName) > 64) ? substr($tableName, 0, 64) : $tableName,
            'column_name' => (strlen($columnName) > 64) ? substr($columnName, 0, 64) : $columnName,
            'field_id' => fake()->random_int(1, 10),
        ];
    }
}
