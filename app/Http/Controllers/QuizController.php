<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
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
}
