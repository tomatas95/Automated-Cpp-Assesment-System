<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exercise>
 */
class ExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'content' => $this->faker->paragraph,
            'hint1' => $this->faker->sentence,
            'hint2' => $this->faker->sentence,
            'hint3' => $this->faker->sentence,
            'difficulty' => $this->faker->randomElement(['easy', 'normal', 'hard']),
            'time_required' => $this->faker->randomElement(['10 minutes', '20 minutes', '3 hours', '1 day']),
            'check1' => $this->faker->numberBetween(1, 100),
            'check1_answer' => $this->faker->numberBetween(1, 100),
            'check2' => $this->faker->numberBetween(1, 100),
            'check2_answer' => $this->faker->numberBetween(1, 100),
            'check3' => $this->faker->numberBetween(1, 100),
            'check3_answer' => $this->faker->numberBetween(1, 100),
        ];
    }
}
