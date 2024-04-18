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
			'title'        => fake()->realText(10),
			'image'        => fake()->imageUrl(),
			'description'  => fake()->realText(50),
			'instructions' => fake()->realText(20),
			'points'       => fake()->numberBetween(1, 10),
			'time'         => fake()->numberBetween(1, 10),
		];
	}
}
