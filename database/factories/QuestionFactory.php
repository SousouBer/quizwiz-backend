<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
	public function definition(): array
	{
		return [
			'text' => fake()->realText(20),
		];
	}
}
