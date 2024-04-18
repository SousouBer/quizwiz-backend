<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DifficultyLevelFactory extends Factory
{
	public function definition(): array
	{
		return [
			'title'            => fake()->word(),
			'color'            => fake()->hexColor(),
			'background_color' => fake()->hexColor(),
		];
	}
}
