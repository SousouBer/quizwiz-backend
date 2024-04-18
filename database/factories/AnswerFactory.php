<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
	public function definition(): array
	{
		return [
			'text'       => fake()->realText(10),
			'is_correct' => fake()->boolean(50),
		];
	}
}
