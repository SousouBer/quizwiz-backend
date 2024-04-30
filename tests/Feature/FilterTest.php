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

	public function test_filter_quizzes_are_successfully_sorted_descending(): void
	{
		$response = $this->json('GET', route('quizzes.index'), ['sort' => 'desc']);

		$response->assertSuccessful();

		$quizTitles = array_map(fn ($quiz) => $quiz['title'], $response->json('data'));

		$descendingTitles = $quizTitles;
		rsort($descendingTitles);

		$this->assertEquals($quizTitles, $descendingTitles);
	}

	public function test_filter_quizzes_are_successfully_sorted_ascending(): void
	{
		$response = $this->json('GET', route('quizzes.index'), ['sort' => 'asc']);

		$response->assertSuccessful();

		$quizTitles = array_map(fn ($quiz) => $quiz['title'], $response->json('data'));

		$descendingTitles = $quizTitles;
		asort($descendingTitles);

		$this->assertEquals($quizTitles, $descendingTitles);
	}

	public function test_filter_quizzes_are_successfully_sorted_oldest(): void
	{
		$response = $this->json('GET', route('quizzes.index'), ['sort' => 'oldest']);

		$response->assertSuccessful();

		$quizCreationDates = array_map(fn ($quiz) => $quiz['created_at'], $response->json('data'));

		$oldestDates = $quizCreationDates;
		sort($oldestDates);

		$this->assertEquals($quizCreationDates, $oldestDates);
	}

	public function test_filter_quizzes_are_successfully_sorted_newest(): void
	{
		$response = $this->json('GET', route('quizzes.index'), ['sort' => 'newest']);

		$response->assertSuccessful();

		$quizCreationDates = array_map(fn ($quiz) => $quiz['created_at'], $response->json('data'));

		$newestDates = $quizCreationDates;
		rsort($newestDates);

		$this->assertEquals($quizCreationDates, $newestDates);
	}

	public function test_filter_quizzes_are_successfully_filtered_that_include_search_value(): void
	{
		$searchValue = 'science';

		$response = $this->json('GET', route('quizzes.index'), ['search' => $searchValue]);

		$response->assertSuccessful();

		$quizzes = $response->json('data');

		foreach ($quizzes as $quiz) {
			$valueInQuizTitle = stripos($quiz['title'], $searchValue) ?? false;

			$valueInCategoryTitle = false;

			foreach ($quiz['categories'] as $category) {
				if (stripos($category['title'], $searchValue) !== false) {
					$valueInCategoryTitle = true;
					break;
				}
			}

			$this->assertTrue($valueInQuizTitle || $valueInCategoryTitle);
		}
	}

	public function test_filter_quizzes_are_successfully_sorted_using_most_popular(): void
	{
		$response = $this->json('GET', route('quizzes.index'), ['sort' => 'popular']);

		$response->assertSuccessful();

		$quizPlaysCount = array_map(fn ($quiz) => $quiz['plays'], $response->json('data'));

		$quizPlaysSorted = $quizPlaysCount;
		sort($quizPlaysSorted);

		$this->assertEquals($quizPlaysCount, $quizPlaysSorted);
	}
}
