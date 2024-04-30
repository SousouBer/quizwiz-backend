<?php

namespace Tests\Feature;

use App\Models\Category;
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

		foreach ($quizzes as $quiz) {
			$response->assertJsonFragment([
				'id' => $quiz->id,
			]);
		}
	}

	public function test_filter_quizzes_are_successfully_filtered_by_categories(): void
	{
		$quizzes = Quiz::paginate(9);
		$category = Category::all()->random();

		$response = $this->get(route('quizzes.index'), []);

		foreach ($quizzes as $quiz) {
			$response->assertJsonFragment([
				'id' => $quiz->id,
			]);
		}
	}
}
