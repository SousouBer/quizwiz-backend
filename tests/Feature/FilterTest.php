<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DifficultyLevel;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterTest extends TestCase
{
	use RefreshDatabase;

	protected function setUp(): void
	{
		parent::setUp();

		$this->seed();
	}

	public function test_filter_returns_quizzes_successfully_if_no_filter_option_is_selected(): void
	{
		$quizzes = Quiz::paginate(9);

		$response = $this->get(route('quizzes.index'));

		$response->assertSuccessful();

		foreach ($quizzes as $quiz) {
			$response->assertJsonFragment([
				'id' => $quiz->id,
			]);
		}
	}

	public function test_filter_quizzes_are_successfully_filtered_by_categories(): void
	{
		$categoryIDs = Category::inRandomOrder()->take(rand(1, Category::count()))->pluck('id')->toArray();

		$categoryIDsString = implode(',', $categoryIDs);

		$response = $this->json('GET', route('quizzes.index'), ['categories' => $categoryIDsString]);

		$response->assertSuccessful();

		foreach ($response->json('data') as $quiz) {
			$ids = array_column($quiz['categories'], 'id');

			$this->assertTrue(!empty(array_intersect($ids, $categoryIDs)));
		}
	}

	public function test_filter_quizzes_are_successfully_filtered_by_level(): void
	{
		$levelIDs = DifficultyLevel::inRandomOrder()->take(rand(1, DifficultyLevel::count()))->pluck('id')->toArray();

		$levelIDsString = implode(',', $levelIDs);

		$response = $this->json('GET', route('quizzes.index'), ['levels' => $levelIDsString]);

		$response->assertSuccessful();

		foreach ($response->json('data') as $quiz) {
			$this->assertTrue(in_array($quiz['difficulty_level']['id'], $levelIDs));
		}
	}
}
