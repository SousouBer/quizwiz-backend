<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
	public function definition(): array
	{
		$ImageUrls = [
			'https://picsum.photos/640/480', // Random image from picsum.photos
			'https://placekitten.com/640/480', // Random kitten image from placekitten.com
			'https://via.placeholder.com/640x480', // Placeholder image from via.placeholder.com
		];

		return [
			'title'        => fake()->words(5, true),
			'image'        => asset('images/default-quiz-image.svg'),
			'description'  => fake()->words(10, true),
			'instructions' => fake()->sentence(),
			'time'         => fake()->numberBetween(1, 10),
		];
	}
}
