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
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

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

	public function similarQuizzes(Quiz $quiz): AnonymousResourceCollection
	{
		$quizzes = Quiz::query()->similarQuizzes($quiz)->get();

		if (!Auth::check()) {
			$quizzes->shuffle();
		}

		return QuizResource::collection($quizzes->take(3));
	}
}
