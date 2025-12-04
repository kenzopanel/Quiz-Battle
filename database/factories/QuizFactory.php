<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\Category::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'timeout_seconds' => fake()->randomElement([60, 90, 120, 180]),
            'per_question_time' => fake()->randomElement([10, 15, 20, 30]),
        ];
    }
}
