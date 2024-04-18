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
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		DifficultyLevel::factory(6)->
		has(Quiz::factory(5)->
		hasAttached(Category::factory()
		->count(rand(1, 9)))
		->has(Question::factory(5)
		->has(Answer::factory()->count(4))))->create();

		User::factory()->create([
			'username'  => 'Test User',
			'email'     => 'test@example.com',
		]);
	}
}
