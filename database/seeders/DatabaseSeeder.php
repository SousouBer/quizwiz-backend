<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Category;
use App\Models\DifficultyLevel;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	public function run(): void
	{
		$categories = Category::factory(20)->create();

		DifficultyLevel::factory(6)->has(
			Quiz::factory(5)->has(
				Question::factory(5)
				->has(Answer::factory()->count(4))
			)->afterCreating(function ($quiz) use ($categories) {
				$quiz->categories()->attach($categories->random(rand(1, 5))->pluck('id'));
			})
		)->create();

		User::factory()->create([
			'username'  => 'Test User',
			'email'     => 'test@example.com',
		]);
	}
}
