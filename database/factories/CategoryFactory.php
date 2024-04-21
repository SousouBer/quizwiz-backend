<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
	private $categories = [
		'History', 'Science', 'Technology', 'Art', 'Literature',
		'Mathematics', 'Music', 'Philosophy', 'Psychology', 'Sociology',
		'Business', 'Health', 'Food', 'Travel', 'Sports',
		'Fashion', 'Entertainment', 'Politics', 'Religion', 'Nature',
	];

	public function definition(): array
	{
		return [
			'title' => fake()->unique()->randomElement($this->categories),
		];
	}
}
