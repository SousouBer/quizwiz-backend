<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Http\Resources\SingleQuizResource;
use App\Models\Quiz;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController
{
	public function index(): AnonymousResourceCollection
	{
		return QuizResource::collection(Quiz::all());
	}

	public function show(int $id): SingleQuizResource
	{
		return new SingleQuizResource(Quiz::findOrFail($id));
	}
}
