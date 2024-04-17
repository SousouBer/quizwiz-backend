<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
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
}
