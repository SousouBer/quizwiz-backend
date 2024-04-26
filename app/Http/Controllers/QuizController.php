<?php

namespace App\Http\Controllers;

use App\Actions\CalculateScore;
use App\Actions\ChangeTimeFormat;
use App\Actions\SaveQuizResults;
use App\Http\Requests\QuizResultsRequest;
use App\Http\Requests\QuizzesRequest;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizResulResource;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController
{
	public function index(QuizzesRequest $request): AnonymousResourceCollection
	{
		$filteringOptions = $request->validated();

		$categoryIDs = $filteringOptions['categories'] ?? null;
		$sort = $filteringOptions['sort'] ?? null;
		$levelIDs = $filteringOptions['levels'] ?? null;
		$search = $filteringOptions['search'] ?? null;
		$myQuizzes = $filteringOptions['my_quizzes'] ?? null;
		$notCompleted = $filteringOptions['not_completed_quizzes'] ?? null;

		$quizzes = Quiz::query();

		if ($search) {
			$quizzes->searchFilter($search);
		}

		if ($categoryIDs) {
			$quizzes->categoryFilter($categoryIDs);
		}

		if ($levelIDs) {
			$quizzes->levelFilter($levelIDs);
		}

		if ($sort) {
			$quizzes->sort($sort);
		}

		if ($myQuizzes xor $notCompleted) {
			if ($myQuizzes) {
				$quizzes->completedQuizzes();
			} else {
				$quizzes->incompletedQuizzes();
			}
		}

		return QuizResource::collection($quizzes->paginate(9));
	}

	public function show(Quiz $quiz): QuizResource
	{
		return QuizResource::make($quiz);
	}

	public function store(QuizResultsRequest $request): QuizResulResource
	{
		$quizResults = $request->validated();

		$quiz = Quiz::with('questions.answers')->findOrFail($quizResults['quiz_id']);

		$calculatedResults = CalculateScore::handle($quiz, $quizResults);

		$timeInMinutes = ChangeTimeFormat::handle($quizResults['time']);

		$finalResults = SaveQuizResults::handle($quiz, $timeInMinutes, $calculatedResults['score'], $calculatedResults['wrong_answers'], $quizResults);

		return QuizResulResource::make($finalResults);
	}

	public function similarQuizzes(Request $request, Quiz $quiz)
	{
		$quizCategories = $quiz->categories->pluck('id');
		$quizzes = Quiz::where(function ($query) use ($quiz) {
			$query->where('id', '!=', $quiz->id)->whereHas('categories', function ($relationQuery) use ($quiz) {
				$relationQuery->whereIn('categories.id', $quiz->categories->pluck('id'));
			})->whereDoesntHave('users', function ($relationQuery) {
				$relationQuery->where('user_id', auth()->user()->id);
			});
		})
		->take(3)
		->get();

		return QuizResource::collection($quizzes);
	}
}
