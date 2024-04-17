<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController
{
	public function index(): AnonymousResourceCollection
	{
		$quizzes = Quiz::with(['categories', 'difficultyLevel', 'questions', 'answers', 'users'])->get();

		return QuizResource::collection($quizzes);
	}

	public function show(int $id): QuizResource
	{
		$quiz = Quiz::with(['categories', 'questions'])->findOrFail($id);

		return QuizResource::make(Quiz::findOrFail($id));
	}
}
