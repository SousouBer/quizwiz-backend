<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
	public function definition(): array
	{
		return [
			'title'        => fake()->words(5, true),
			'image'        => fake()->imageUrl(),
			'description'  => fake()->words(10, true),
			'instructions' => fake()->sentence(),
			'points'       => fake()->numberBetween(1, 10),
			'time'         => fake()->numberBetween(1, 10),
		];
	}
}
