<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DifficultyLevelFactory extends Factory
{
	private $difficultyLevels = ['Starter', 'Beginner', 'Middle', 'High', 'Very High', 'Dangerously High'];

	private $index = 0;

	private function getElementsByOrder()
	{
		$element = $this->difficultyLevels[$this->index];

		$this->index = ($this->index + 1) % count($this->difficultyLevels);

		return $element;
	}

	public function definition(): array
	{
		return [
			'title'            => $this->getElementsByOrder(),
			'color'            => fake()->hexColor(),
			'background_color' => fake()->hexColor(),
		];
	}
}
