<?php

namespace Database\Factories\History;

use App\Models\Master\Slider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\SliderHistory>
 */
class SliderHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $slider = fake()->randomElement(Slider::all()->toArray());

        return [
            'slider_id' => $slider->id,
            'title' => $slider->title,
            'slug' => $slider->slug,
            'link' => $slider->link,
            'image' => $slider->image,
            'status' => $slider->status,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
