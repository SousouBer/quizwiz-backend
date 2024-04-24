<?php

namespace App\Http\Controllers;

use App\Actions\CalculateScore;
use App\Actions\ChangeTimeFormat;
use App\Actions\SaveQuizResults;
use App\Http\Requests\QuizResultsRequest;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizResulResource;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class QuizController
{
	public function index(Request $request): AnonymousResourceCollection
	{
		$categoryIDs = $request->query('categories');
		$sort = $request->query('sort');
		$levelIDs = $request->query('levels');
		$search = $request->query('search');

		$quizzes = Quiz::query();

		if ($search) {
			$quizzes->where(function ($query) use ($search) {
				$query->where('title', 'like', '%' . $search . '%')
					  ->orWhereHas('categories', function ($query) use ($search) {
					  	$query->where('title', 'like', '%' . $search . '%');
					  });
			});
		}

		if ($categoryIDs) {
			$categoryIdsArray = explode(',', $categoryIDs);

			$quizzes->whereHas('categories', function ($query) use ($categoryIdsArray) {
				$query->whereIn('categories.id', $categoryIdsArray);
			});
		}

		if ($levelIDs) {
			$levelIDsArray = explode(',', $levelIDs);
			$quizzes->whereIn('difficulty_level_id', $levelIDsArray);
		}

		if ($sort) {
			switch ($sort) {
				case 'asc':
					$quizzes->orderBy('title', 'asc');
					break;
				case 'desc':
					$quizzes->orderBy('title', 'desc');
					break;
				case 'oldest':
					$quizzes->oldest();
					break;
				case 'newest':
					$quizzes->latest();
					break;
				case 'popular':
					$quizzes->select('quizzes.*', DB::raw('COUNT(quiz_user.quiz_id) as plays'))
					->leftJoin('quiz_user', 'quizzes.id', '=', 'quiz_user.quiz_id')
					->groupBy('quizzes.id');

					$quizzes->orderBy('plays', 'desc');
					break;
				default:
					break;
			}
		}

		// $filteredQuizzes = $quizzes->paginate(10);

		// return QuizResource::collection($filteredQuizzes);

		// $quizzes = Quiz::paginate(9);

		return QuizResource::collection($quizzes->get());
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
}
