<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Clockwork\Request\Request;
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

	public function store(Request $request)
	{
		$arr = [
			'quiz_id' => 3,
			'time'    => '4:56',
			'answers' => [],
		];

		$quiz = Quiz::with('questions.answers')->findOrFail($arr['quiz_id']);

		$result = $quiz->calculateScore($arr, $arr['time']);

		return response()->json($result);
	}
}
