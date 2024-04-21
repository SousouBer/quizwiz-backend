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
		return QuizResource::collection(Quiz::all());
	}

	public function show(Quiz $quiz): QuizResource
	{
		return QuizResource::make($quiz);
	}

	public function store(QuizResultsRequest $request, CalculateScore $calculateScore, ChangeTimeFormat $changeTimeFormat, SaveQuizResults $saveQuizResults): QuizResulResource
	{
		$quizResults = $request->validated();

		$quiz = Quiz::with('questions.answers')->findOrFail($quizResults['quiz_id']);

		$calculatedResults = $calculateScore->handle($quiz, $quizResults);

		$timeInMinutes = $changeTimeFormat->handle($quizResults['time']);

		$finalResults = $saveQuizResults->handle($quiz, $timeInMinutes, $calculatedResults['score'], $calculatedResults['wrong_answers'], $quizResults);

		return QuizResulResource::make($finalResults);
	}
}
