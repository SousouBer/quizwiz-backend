<?php

namespace App\Http\Controllers;

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

	public function store(QuizResultsRequest $request): QuizResulResource
	{
		$quizResults = $request->validated();

		$quiz = Quiz::with('questions.answers')->findOrFail($quizResults['quiz_id']);

		$finalResults = $quiz->calculateScore($quizResults, $quizResults['time']);

		return QuizResulResource::make($finalResults);
	}
}
