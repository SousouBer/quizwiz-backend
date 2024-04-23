<?php

namespace App\Http\Controllers;

use App\Actions\CalculateScore;
use App\Actions\ChangeTimeFormat;
use App\Actions\SaveQuizResults;
use App\Http\Requests\QuizResultsRequest;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizResulResource;
use App\Models\Quiz;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController
{
	public function index(): AnonymousResourceCollection
	{
		$quizzes = Quiz::paginate(9);

		return QuizResource::collection($quizzes);
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
